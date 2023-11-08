<?php
 
namespace App\Http\Controllers\AutoDeleteController;
 
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
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class delete_all
{
    public function __construct($obj_name, $obj_id, $depth){
        self::$obj_name = $obj_name;
        self::$obj_id = $obj_id;
        self::$depth = $depth;
    }
    public static $obj_name;
    public static $obj_id;
    public static $depth;
    public function delete($delete_mode)
    {     
        $obj_name = self::$obj_name;
        $obj_id = self::$obj_id;
        $depth = self::$depth;

        if ($delete_mode < $depth && $delete_mode != 3) return;

        $obj_type = 'App\Models\\'.$obj_name;
        $object = $obj_type::where('id', $obj_id)->first();

        $types = Objecttype::all() -> toArray();

        foreach($types as $type){
            $connected_objs = ([]);

            $conn_obj_name = $type['name'];
            $conn_obj_type = 'App\Models\\'.$conn_obj_name;
            
            if ($conn_obj_name == $obj_name) continue;

            if (Schema::hasColumn(with(new $conn_obj_type) -> getTable(), $obj_name.'_id'))
            {
            $connected_objs = $conn_obj_type::where($obj_name.'_id', $obj_id)
            -> get() -> toArray();
        }

        if (Schema::hasColumn(with(new $conn_obj_type) -> getTable(), 'group_id')
         && Schema::hasColumn(with(new $conn_obj_type) -> getTable(), 'user_id')
            && $obj_name == 'Group_user'){
            $group_id = $object -> group_id;
            $user_id = $object -> user_id;
                
            $connected_objs = $conn_obj_type::where('group_id', $group_id)
            ->where('user_id', $user_id) -> get() -> toArray();
            }

            foreach ($connected_objs as $obj)
            {
                $delete = new delete_all($conn_obj_name, $obj['id'], $depth + 1);
                $delete -> delete($delete_mode);
            }
        }

        if ($object != null && ($depth > 1 || $obj_name != 'group_user')) $object->delete();
    }
}