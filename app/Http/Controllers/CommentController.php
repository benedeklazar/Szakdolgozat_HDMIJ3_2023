<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\AutoDeleteController\Count;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
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
class CommentController extends Controller
{
    public function data($post_id)
    {
        $list = Comment::all()->where('post_id', $post_id)
        ->sortByDesc('created_at');

        if ($this->isAdmin()) return $list;
        $result = collect([]);

        foreach($list as $item)
        {
            $active = $item -> status -> name == "active";

            if ($active)
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

        $data = Comment::where('id', $id) -> first();

        if ($data === null) return view('comment.profile.404');
       
        $status = $data -> status -> name;
        $post = $data -> post;

        $postStatus = null; $postScheduled = true; $hasRight = false; $group = null; $isMember = false;
        $isLogged = $this->auth('id') == $data -> user_id;
        
        if ($post != null) {
            $group = $post -> group;
            $postStatus = $post -> status -> name;
            $postScheduled = ($post -> created_at) > Carbon::now();
            $hasRight = $isLogged || $this->hasRight("review_hidden post", $data -> post -> id);
            $isMember =  $group != null && $this->member($group -> id); 
        }

        if (!$this->isAdmin() && !$hasRight &&
        ($status != "active" || $postStatus != "active" || $postScheduled || !$isMember)) return view('comment.profile.locked');

        return view('comment.profile.'.$status, [
            'comment' => $data,

            'isAdmin' => $this->isAdmin(),
            'isLogged' => $isLogged,
        ]);      
    }

    public function create_form($post_id)
    {
        $post = Post::where('id', $post_id)->first();

        if(!(
            $post -> status -> name == "active" &&
            $this->isLogged() &&
            $this->isActive() &&
            $this->member($post -> group -> id)
        ))return redirect()->back();

        return view('comment.create', [
            'post_id' => $post_id,
            'post' => $post
        ]); 
    }

    public function create(Request $data, $post_id)
    {
        $data->validate([
            'text' => ['string', 'min:5', 'max:255'],
        ]);

        $comment = Comment::create([
            'text' => $data['text'],
            'post_id' => $post_id,
            'user_id' => $this->auth('id'),

            'status_id' => 1,
            'created_at' => Carbon::now()
        ]);

        return redirect()->to('/post/'.$post_id);
    }


    public function edit($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isObjectOwner("comment", $id)
        ))return redirect()->back();

        $data = Comment::where('id', $id) -> first();

        if ($data === null) return view('comment.error.404');

        return view('comment.edit', [
            'comment' => $data,
            'text' => $data -> text,
            'isAdmin' => $this->isAdmin()
        ]); 
    }

    public function update(Request $data, $id)
    {
        $data->validate([
            'text' => ['string', 'min:5', 'max:255'],
        ]);

        $new = Comment::where('id', $id) -> update([
            'text' => $data['text'],
        ]);

        return redirect()->to('/comment/'.$id);
    }

    public function delete($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isObjectOwner("comment", $id)
        ))return redirect()->back();


        $obj_name = "comment";
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
            $this->hasRight("remove comment", $id)
        ))return redirect()->back();

        $obj_name = "comment";
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