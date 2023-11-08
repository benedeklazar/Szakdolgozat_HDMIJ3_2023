<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\AutoDeleteController\Count;
use Carbon\Carbon;
use App\Models\Group;
use App\Models\Group_user;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use App\Models\Remove;
use App\Models\Objecttype;
use App\Models\Visibility;
use App\Http\Controllers\RemoveController;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class GroupController extends Controller
{
    public function data()
    {
        $list = Group::all();
        if ($this->isAdmin()) return $list;
        $result = collect([]);

        foreach($list as $item)
        {
            if ($item -> status -> name == "active" && 
            ($item -> visibility -> name == "public" ||
            Group_user::where('group_id', $item -> id)
            ->where('user_id', $this->auth('id'))->first() != null))
            {
                $result -> push($item);
            }
        }
        return $result;
    }
    public function list()
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();
        
        return view('group.list', [
            'groups' => self::data()
        ]);
    }

    public function profile($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $data = Group::where('id', $id) -> first();

        if ($data === null) return view('group.profile.404');

        $owner_id = $data -> user_id;
       
        $status = $data -> status -> name;
        $group_user = Group_user::where('group_id', $id)
        ->where('user_id', $this->auth('id')) -> first();

        if ($group_user === null) $member_status = 'guest';
        else $member_status = $group_user -> status -> name;

        $isGroupAdmin = Group_user::where('group_id', $id)
        ->where('user_id', $this->auth('id'))->where('role_id', 1)
        ->first();
 
        $isOwner = $this->auth('id') == $owner_id;

        return view('group.profile.'.$status, [
            'group' => $data,
            'status' => $member_status,

            'isAdmin' => $this->isAdmin(),
            'isOwner' => $isOwner || $isGroupAdmin,
        ]);      
    }

    public function edit($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isObjectOwner("group", $id)
        ))return redirect()->back();

        $data = Group::where('id', $id) -> first();

        if ($data === null) return view('group.error.404');

        return view('group.edit', [
            'group' => $data,
            'name' => $data -> name,
            'visibility_id' => $data -> visibility_id,
            'roles' => Role::get(),
            'visibilities' => Visibility::get(),
            'new_member_status_id' => $data -> new_member_status_id,
            'statuses' => Status::get(),
            'isAdmin' => $this->isAdmin()
        ]); 
    }

    public function update(Request $data, $id)
    {
        $data->validate([
            'name' => ['string', 'min:5', 'max:255', Rule::unique('groups')->ignore($id)],
        ]);

        $new = Group::where('id', $id) -> update([
            'name' => $data['name'],
            'visibility_id' => $data['visibility_id'],
            'new_member_status_id' => $data['new_member_status_id'],
        ]);

        return redirect()->to('/group/'.$id);
    }

    public function delete($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isObjectOwner("group", $id) || $this->isAdmin()
        ))return redirect()->back();


        $obj_name = "group";
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
            $this->isAdmin()
        ))return redirect()->back();

        $obj_name = "group";
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

    public function create_form()
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        return view('group.create', [
            'isAdmin' => $this->isAdmin()
        ]); 
    }

    public function create(Request $data)
    {
        $data->validate([
            'name' => ['string', 'min:5', 'max:255', Rule::unique('groups')],
        ]);

        $group = Group::create([
            'name' => $data['name'],
            'user_id' => $this->auth('id'),
            'status_id' => 1,
            'visibility_id' => $data['visibility_id'],
            'new_member_status_id' => $data['new_member_status_id'],
        ]);

        $member = Group_user::create([
            'group_id' => $group -> id,
            'user_id' => $this->auth('id'),
            'role_id' => 1,
            'status_id' => 1,
        ]);

        return redirect()->to('/group/'.$group -> id);
    }
}