<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\AutoDeleteController\Count;
use Carbon\Carbon;
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
class UserController extends Controller
{
    public function data()
    {
        $list = User::with(['role'])
        ->select('users.*')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        ->get();
        if ($this->isAdmin()) return $list;
        $result = collect([]);
        foreach($list as $item)
        {
            if ($item->status->name == "active")
            $result -> push($item);
        }
        return $result;
    }
    public function list()
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();
 
        return view('user.list', [
            'users' => self::data()
        ]);
    }

    public function profile($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $data = User::where('id', $id) -> first();

        if ($data === null) return view('user.profile.404');
       
        $status = $data -> status -> name;

        return view('user.profile.'.$status, [
            'user' => $data,

            'isAdmin' => $this->isAdmin(),
            'isLogged' => $this->auth('id') == $id,
        ]);      
    }

    public function edit($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isOwner($id)
        ))return redirect()->back();

        $data = User::where('id', $id) -> first();

        if ($data === null) return view('user.error.404');

        return view('user.edit', [
            'user' => $data,
            'username' => $data -> username,
            'role_id' => $data -> role_id,
            'roles' => Role::get(),
            'status_id' => $data -> status_id,
            'statuses' => Status::get(),
            'isAdmin' => $this->isAdmin(),
        ]); 
    }

    public function update(Request $data, $id)
    {
        $data->validate([
            'username' => ['string', 'min:5', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => ['string', 'nullable', 'min:5', 'confirmed'],
            'image' => ['image', 'file', 'nullable']
        ]);

        if ($image = $data->file('image')) {
            
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage"; 
            
            User::where('id', $id) -> update([
                'image' => $input['image']
            ]);
        }     

        $password = Hash::make($data -> password);
        if ($data['password'] == null)
        $password = User::where('id', $id) -> first() -> password;

        $role_id = $data -> role_id;
        if ($data['role_id'] == null)
        $role_id = User::where('id', $id) -> first() -> role_id;

        $new = User::where('id', $id) -> update([
            'username' => $data['username'],
            'password' => $password,

            'role_id' => $role_id,
        ]);

        return redirect()->to('/user/'.$id);
    }

    public function delete($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isOwner($id)
        ))return redirect()->back();

        $obj_name = "user";
        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $id)->first();
        $values = Count::pre_count($obj_name, $id);

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

        $obj_name = "user";
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