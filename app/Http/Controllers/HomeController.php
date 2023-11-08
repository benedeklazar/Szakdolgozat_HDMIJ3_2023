<?php

namespace App\Http\Controllers;
 
use Carbon\Carbon;
use App\Models\Remove;
use App\Models\Post;
use App\Models\Objecttype;
use App\Models\Group_user;
use App\Models\Group;
use App\Http\Controllers\RemoveController;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data()
    {
        return Remove::with(['objecttype'])
        ->select('removes.*')
        ->leftJoin('objecttypes', 'objecttypes.id', '=', 'removes.object_type')
        ->get();
    }

    public function item($id)
    {
        return Remove::where('id', $id)->first();
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list()
    {
        self::auto_delete();

        if(!(
            $this->isLogged()
        ))return redirect()->back();
      
        $types = Objecttype::all();
        $others = collect([]);

        foreach($types as $type){
        $objects = collect([]);
        $model = 'App\Models\\'.$type -> name;

        if (!Schema::hasColumn(with(new $model) -> getTable(), 'user_id')) continue;

        $objects = $model::all()->where('user_id', $this->auth('id'));
            foreach($objects as $object){
        $others -> push($object);
        }
    }
        //hogyha tiltva van a felhasználó, akkor azt sorolja előre
        $all_removes = self::data();
        $removes = $all_removes->where('object_id', $this->auth('id'))
        ->where('object_type', 1);

        foreach($others as $other){
            $type = Objecttype::where('name', class_basename($other))->first();

            $remove = $all_removes
            ->where('object_type', $type -> id) 
            ->where('object_id', $other -> id)
            ->first();

            if ($remove != null) $removes -> push($remove);
        }

        return view('home', [
            'removes' => $removes,
        ]);
    }

    public function appeal_list()
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();
      
        $list = collect([]);
        if ($this->auth('role_id') == 1) 
        {$list = self::data()->where('appeal_status', 2);}
        else
        {$removes = self::data()->where('appeal_status', 2) -> toArray();
        
        foreach($removes as $remove){
            $remove = Remove::where('id', $remove['id']) -> first();
            $obj_name = $remove -> objecttype -> name;
            $model = 'App\Models\\'.$obj_name;
            $object = $model::where('id', $remove -> object_id)->first();

            if ($object == null) continue;

            if ($obj_name == 'Group') $group = $object -> id;
            else $group = $object -> group_id;
            
            if ($this->hasRight('review_appeals group', $group))
            $list -> push(Remove::where('id', $remove['id']) -> first());
            }
        }
        
        return view('removes.appeal_list', [
            'appeals' => $list,
        ]);
    }

    public function auto_delete()
    {
        $removes = Remove::all()->where('deletion_time', "<=", Carbon::now());

        foreach($removes as $remove)
        {
            $obj_name = $remove -> objecttype -> name;
            $obj_id = $remove -> object_id;

            $model = 'App\Models\\'.$obj_name;
            $object = $model::where('id', $obj_id) -> first();

            //ha nem létezik az objektum, akkor is törölni kell a hozzá tartozó objektumokat.
            if ($object == null) $status = 'not_active'; 
            else $status = $object -> status -> name;

            if ($status != 'active') $status = 'not_active';

            $path = 'App\Http\Controllers\AutoDeleteController\\';
            if ($remove -> appeal_stat -> name == "_sent") $class = $path.'Appeal_sent';
            else $class = $path.'Status_'.$status;

            $strategy = new $class($obj_name, $obj_id, 0);
            $strategy -> auto_delete($remove -> id);
        }
    }
}
