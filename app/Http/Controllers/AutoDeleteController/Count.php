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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class Count
{
    public function __construct($obj_name, $obj_id, $depth, $array){
        self::$obj_name = $obj_name;
        self::$obj_id = $obj_id;
        self::$depth = $depth;
        self::$array = $array;
    }
    public static $obj_name;
    public static $obj_id;
    public static $depth;
    public static $array;
    
    public static function pre_count($obj_name, $id)
    {
        $array = [['type' => $obj_name, 'depth' => 0, 'id' => $id]];

        $class = 'App\Http\Controllers\AutoDeleteController\Count';

        $count = new $class($obj_name, $id, 1, $array);
        $array = array_unique($count -> count(), SORT_REGULAR);

        $items = ([]);
        $first = ([]);
        $referred = ([]);
        $all = ([]);
        
        foreach($array as $item)
        {
            $items = array_merge($items,[[
            'type' => $item['type'],
            'depth' =>$item['depth'],
            'id' => $item['id'] ]]);
        }

        $filtered = ([]);
        foreach($items as $item){
            $min = $item;
            foreach ($items as $other){
                if ($item['type'] == $other['type'] && $item['id'] == $other['id']
                && $other['depth'] < $item['depth'])
                {
                    $min = $other;
                }   
            }
            $filtered = array_merge($filtered,[$min]);
        }

        $filtered = array_unique($filtered, SORT_REGULAR);
        
        foreach($filtered as $item)
        {
            if ($item['depth'] < 1) $first = array_merge($first, [$item['type']]);
            if ($item['depth'] <= 1) $referred = array_merge($referred, [$item['type']]);
            $all = array_merge($all, [$item['type']]);
        }

        return ['all' => $all, 'referred' => $referred, 'first' => $first];

    }
    public function count()
    {
        $obj_name = self::$obj_name;
        $obj_id = self::$obj_id;
        $depth = self::$depth;
        $array = self::$array;

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
                 && $obj_name == 'group_user'){
            $group_id = $object -> group_id;
            $user_id = $object -> user_id;
                
            $connected_objs = $conn_obj_type::where('group_id', $group_id)
            ->where('user_id', $user_id) -> get() -> toArray();
            }

            foreach ($connected_objs as $obj)
            {            
                $obj['type'] = $conn_obj_name;
                $obj['depth'] = $depth;
                $array = array_merge($array, [$obj]);
                $strategy = new Count($conn_obj_name, $obj['id'], $depth + 1, $array);
                $result = $strategy -> count();
                $array = array_merge($array, $result);
            }
        }

        return array_unique($array, SORT_REGULAR);
        
    }
}