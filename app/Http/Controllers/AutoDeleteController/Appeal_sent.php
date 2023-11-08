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
class Appeal_sent
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

        $obj_type = 'App\Models\\'.$obj_name;
        $object = $obj_type::where('id', $obj_id)->first();

        if ($object != null) $object->update(['status_id' => 1]);

        Remove::where('id', $id)->update([
            'appeal_status' => 4,
            'reason' => "A tiltás lejárt!",
            'deletion_time' => Carbon::now()->addDays(8),]);
    }
}