<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\AutoDeleteController\Count;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Role;
use App\Models\Status;
use App\Models\Remove;
use App\Models\Objecttype;
use App\Models\Group;
use App\Http\Controllers\RemoveController;
use App\Models\Visibility;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class PostController extends Controller
{
    public function data($group_id)
    {
        $list = Post::all()->where('group_id', $group_id)
        ->sortByDesc('created_at');

        if ($this->isAdmin()) return $list;
        
        $result = collect([]);

        foreach($list as $item)
        {
            $scheduled = Carbon::now() -> diffInSeconds($item -> created_at, false) > 0;
            $active = $item -> status -> name == "active";
            $visible = $item -> visibility -> name == "public";

            if (($visible && !$scheduled && $active) ||
            $this->hasRight("review_hidden post", $item -> id) ||
            ($item -> user != null && $item -> user -> id == $this->auth('id')))
            {
                $result -> push($item);
            }
        }
        
        return $result;
    }

    public function profile($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $data = Post::where('id', $id) -> first();

        if ($data === null) return view('post.profile.404');
       
        $status = $data -> status -> name;
        $isLogged = $this->auth('id') == $data -> user_id;

        $scheduled = Carbon::now() -> diffInSeconds($data -> created_at, false) > 0;

        if ($data -> group == null || $this->member($data -> group -> id) == null)
        {
            $isActiveMember = false; $isActiveGroup = false;
        }
        else 
        {
            $isActiveMember = $this->member($data -> group -> id)
            -> status -> name == "active";
            $isActiveGroup = $data -> group -> status -> name == "active";
        }

        $hasRight = $isLogged || $this->hasRight("review_hidden post", $data -> id);

        if (!$hasRight && $scheduled) $status = "locked.scheduled";
        if (!$hasRight && $data -> visibility -> name == "private") $status = "locked.private";

        if (!$isActiveMember) $status = "locked.not_member";
        elseif (!$isActiveGroup) $status = "locked.deleted_group";

        return view('post.profile.'.$status, [
            'post' => $data,

            'isAdmin' => $this->isAdmin(),
            'isLogged' => $isLogged,
        ]);      
    }

    public function create_form($group_id)
    {
        $group = Group::where('id', $group_id)->first();

        if(!(
            $group -> status -> name == "active" &&
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight("create_post group", $group_id)
        ))return redirect()->back();

        return view('post.create', [
            'visibilities' => Visibility::get(),
            'group_id' => $group_id
        ]); 
    }

    public function create(Request $data, $group_id)
    {
        $data->validate([
            'text' => ['string', 'min:5', 'max:255'],
            'image' => ['image', 'file', 'nullable'],
            'created_at' => ['date', 'nullable', 'after:now']
        ]);

        $created_at = $data -> created_at;
        if ($data['created_at'] == null)
        $created_at = Carbon::now();

        $post = Post::create([
            'text' => $data['text'],
            'group_id' => $group_id,
            'user_id' => $this->auth('id'),

            'status_id' => 1,
            'visibility_id' => $data['visibility_id'],
            'created_at' => $created_at
        ]);

        if ($image = $data->file('image')) {
            
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage"; 
            
            Post::where('id', $post -> id) -> update([
                'image' => $input['image']
            ]);
        }     

        return redirect()->to('/post/'.$post -> id);
    }


    public function edit($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isObjectOwner("post", $id)
        ))return redirect()->back();

        $data = Post::where('id', $id) -> first();

        if ($data === null) return view('post.error.404');

        return view('post.edit', [
            'post' => $data,
            'text' => $data -> text,
            'visibility_id' => $data -> visibility_id,
            'visibilities' => Visibility::get(),
            'isAdmin' => $this->isAdmin()
        ]); 
    }

    public function update(Request $data, $id)
    {
        $data->validate([
            'text' => ['string', 'min:5', 'max:255'],
        ]);

        $new = Post::where('id', $id) -> update([
            'text' => $data['text'],
            'visibility_id' => $data['visibility_id'],
        ]);

        return redirect()->to('/post/'.$id);
    }

    public function delete($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isObjectOwner("post", $id)
        ))return redirect()->back();


        $obj_name = "post";
        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $id)->first();
        $values = Count::pre_count($obj_name, $id);

        if ($object == null || $object -> status -> name != "active")
        return redirect()->back();

        return view('removes.create_delete', [
            'id' => $id,
            'obj_name' => $obj_name,
            'object' => $object,

            'all' => array_count_values($values['all']),
            'referred' => array_count_values($values['referred']),
            'first' => array_count_values($values['first']),
        ]);
    }

    public function remove($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight("remove post", $id)
        ))return redirect()->back();

        $obj_name = "post";
        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $id)->first();
        $values = Count::pre_count($obj_name, $id);

        return view('removes.create_remove', [
            'id' => $id,
            'obj_name' => $obj_name,
            'object' => $object,

            'all' => array_count_values($values['all']),
            'referred' => array_count_values($values['referred']),
            'first' => array_count_values($values['first']),
        ]);
    }
}