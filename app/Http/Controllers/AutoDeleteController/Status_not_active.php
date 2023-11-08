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
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class Status_not_active
{
    public function __construct($obj_name, $obj_id, $depth){
        self::$obj_name = $obj_name;
        self::$obj_id = $obj_id;
        self::$depth = $depth;
    }
    public static $obj_name;
    public static $obj_id;
    public static $depth;
    public function auto_delete($id)
    {
        $obj_name = self::$obj_name;
        $obj_id = self::$obj_id;
        $depth = self::$depth;

        $delete_mode = Remove::where('id', $id)->first()->delete_mode;

            $delete = new delete_all($obj_name, $obj_id, $depth + 1);
            $delete -> delete($delete_mode);

        Remove::where('id', $id)->delete();
    }
}