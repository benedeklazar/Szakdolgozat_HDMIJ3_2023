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
use App\Http\Controllers\RemoveController;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class Group_userController extends Controller
{
    public function data($id){
        $list = Group_user::all()->where('group_id', $id);
        
        if ($this->isAdmin() || $this->hasRight('list_hidden_members group', $id)) return $list;
        $result = collect([]);

        foreach($list as $item)
        {
            if ($item -> status -> name == "active" ||
            $item -> user_id == $this->auth('id'))
            {
                $result -> push($item);
            }
        }
        return $result;
    }

    public function item($id)
    {
        return Group_user::where('id', $id)->first();
    }
    public function members($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->member($id) != null || $this->isAdmin()
        ))return redirect()->back();

            $group = Group::where('id', $id)->first();
            if ($group == null) return view('group.profile.404');
            $group_status = $group -> status -> name;
            if ($group_status != 'active') return view('group.profile.'.$group_status,
        ['isAdmin' => $this->isAdmin(),]);

        return view('group_user.list', [
            'members' => self::data($id),
            'group' => $group,

            'isOwner' => $this->isObjectOwner("group", $id),
            'isAdmin' => $this->isAdmin()
        ]);
    }
    public function join($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $current_membership = Group_user::where('group_id', $id)
        ->where('user_id', $this->auth('id'))->first();

        $group = Group::where('id', $id) -> first();

        $new_member_status = $group -> new_member_status_id;
        
        if ($current_membership != null) return redirect()->back();
        if ($new_member_status == null) return redirect()->back();

        if ($group -> visibility -> name == "private") return redirect()->back();

        Group_user::create([
            'group_id' => $id,
            'user_id' => $this->auth('id'),
            'role_id' => 3,
            'status_id' => $new_member_status
        ]);

        return redirect()->to('/group/'.$id);
    }

    public function left($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $current_membership = self::item($id);

        if ($current_membership == null) return redirect()->back();
        if ($current_membership -> user_id != $this->auth('id')) return redirect()->back();

        $membership_status = $current_membership -> status -> name;

        if ($membership_status == 'ban') return redirect()->back();
        //a csoport tulajdonosa ne tudjon kilépni a saját csoportjából:
        if ($current_membership -> group -> user_id == $this->auth('id'))
        return redirect()->back();

        $current_membership -> delete();

        return redirect()->to('/group/member/'.$id);
    }

    public function accept($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $current_membership = self::item($id);

        if ($current_membership == null) return redirect()->back();
        if ($current_membership -> user_id != $this->auth('id')) return redirect()->back();

        $membership_status = $current_membership -> status -> name;

        if ($membership_status != 'invited') return redirect()->back();

        $current_membership -> update(['status_id' => 1]);

        return redirect()->to('/group/member/'.$id);
    }

    public function profile($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $data = self::item($id);

        if ($data === null) return view('group_user.profile.404');

        $owner_id = null;
        if ($data -> group != null)
        $owner_id = $data -> group -> user_id;

        $user_id = $data -> user_id;
        $group_id = $data -> group_id;

        $isGroupAdmin = Group_user::where('group_id', $group_id)
        ->where('user_id', $this->auth('id'))->where('role_id', 1)
        ->first();
 
        $isOwner = $this->auth('id') == $owner_id;

        return view('group_user.profile.active', [
            'group_user' => $data,

            'isAdmin' => $this->isAdmin(),
            'isOwner' => $isOwner || $isGroupAdmin,
            'isLogged' => $this->auth('id') == $user_id,
        ]);      
    }

    public function kick($id)
    {
        $member = self::item($id);

        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight('remove group_user', $id)
        ))return redirect()->back();

        $membership_status = $member -> status -> name;
        $group_id = $member -> group_id;
        if ($membership_status == 'ban' &&
        !($this->isObjectOwner("group", $group_id) || $this->isAdmin())) return redirect()->back();

        $member -> delete();

        return redirect()->to('/group/member/'.$id);   
    }

    public function ban($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight('remove group_user', $id)
        ))return redirect()->back();

        $obj_name = "group_user";
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

    public function admiss($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight('edit group_user', $id)
        ))return redirect()->back();

        $current_membership = self::item($id);

        if ($current_membership == null) return redirect()->back();

        $membership_status = $current_membership -> status -> name;

        if ($membership_status != 'pending') return redirect()->back();

        $current_membership -> update(['status_id' => 1]);

        return redirect()->to('/group/member/'.$id);   
    }

    public function edit($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight('edit group_user', $id)
        ))return redirect()->back();

        $data = self::item($id);

        $group = $data -> group;
        if ($group == null) return view('group.profile.404');

        $logged_member = Group_user::where('group_id', $group -> id)->
        where('user_id', $this->auth('id'))->first();

        $role = null;
        if ($logged_member != null) 
        $role = $logged_member -> role;

        return view('group_user.edit', [
            'id' => $id,
            'group_user' => $data,
            'logged_role' => $role,
            'role_id' => $data -> role_id,
            'roles' => Role::get(),

            'isAdmin' => $this->isAdmin(),
            'isOwner' => $this->isObjectOwner("group", $group -> id),
        ]);  
    }

    public function update(Request $data, $id)
    {
        $data->validate([
            'role_id' => "required",
        ]);
        $member = self::item($id);

        $member->update([
            'role_id' => $data['role_id'],
        ]);

        return redirect()->to('/group/member/'.$member -> id);
    }

    public function create_form($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->hasRight('invite_member group', $id)
        ))return redirect()->back();


        return view('group_user.create', [
            'group' => Group::where('id', $id)->first(),
            'users' => User::all()->where('status_id', 1),

            'isAdmin' => $this->isAdmin(),         
        ]);  
    }
    public function create(Request $data, $id)
    {
        $data->validate([
            'user_id' => "required",
        ]);

        $member = Group_user::create([
            'group_id' => $id,
            'user_id' => $data['user_id'],
            'role_id' => 3,
            'status_id' => 5,
        ]);

        return redirect()->to('/group/member/'.$member -> id);
    }
}