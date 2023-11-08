<?php
 
namespace App\Http\Controllers;
 
use Carbon\Carbon;
use App\Models\Remove;
use App\Models\User;
use App\Models\Objecttype;
use App\Models\Group;
use App\Models\Role;
use App\Models\Status;
use Exception;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class RemoveController extends Controller
{
    public function item($id)
    {
        return Remove::where('id', $id)->first();
    }
    public function remove(Request $data, $obj_name, $id)
    {
        $data->validate([
            'reason' => ['string', 'min:10', 'max:255'],
        ]);

        $obj_type = Objecttype::where('name', $obj_name) -> first() -> id;

        Remove::where('object_type', $obj_type)
                        ->where('object_id', $id)->delete();

        Remove::create([
            'object_type' => $obj_type,
            'object_id' => $id,
            'reason' => $data['reason'],
            'appeal' => null,
            'appeal_status' => 1,
            'deletion_time' => Carbon::now()->addDays(8),
            'delete_mode' => $data['delete_mode']
        ]);

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $id)->first(); 

        if ($obj_name != 'group_user'){
         $object -> update([
            'status_id' => 3]);} //tiltás
            else{
        $object -> update([
            'status_id' => 6]);} //tag bannolás

        return redirect()->to('/');
    }

    public function delete(Request $data, $obj_name, $id)
    {
        $data->validate([]);

        $obj_type = Objecttype::where('name', $obj_name)->value('id');
        
        Remove::where('object_type', $obj_type)
              ->where('object_id', $id)->delete();

        Remove::create([
            'object_type' => $obj_type,
            'object_id' => $id,
            'reason' => "te törölted!",
            'appeal' => "-",
            'appeal_status' => 4,
            'deletion_time' => Carbon::now()->addDays(8),
            'delete_mode' => $data['delete_mode']
        ]);

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $id)->first(); 

        $object -> update([
            'status_id' => 2
        ]);

        return redirect()->to('/');
    }

    public function pre_restore($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isDefendant($id)
        ))return redirect()->back();

        $remove = self::item($id);
        $obj_name = $remove -> objecttype -> name;
        $obj_id = $remove -> object_id;
        
        $appeal_status = $remove -> appeal_stat -> name;

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $obj_id)->first();

        if ($object == null) $status = "removed";
        else $status = $object -> status -> name;

        return view('removes.restore.'.$status.$appeal_status, [
            'id' => $id,
            'object' => $object,
            'obj_name' => $obj_name,
            'reason' => $remove -> reason
        ]);
    }

    public function pre_review($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive() &&
            $this->isClaimant($id)
        ))return redirect()->back();

        $remove = self::item($id);
        if ($remove -> appeal_status != 2) return redirect()->back();

        $obj_name = $remove -> objecttype -> name;
        $obj_id = $remove -> object_id;

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $obj_id)->first();

        return view('removes.review',[
            'id' => $id,
            'object' => $object,
            'obj_name' => $obj_name,
            'reason' => $remove -> reason,
            'appeal' => $remove -> appeal
        ]);
    }

    public function delete_restore(Request $data, $id)
    {
        $data->validate([]);

        $remove = self::item($id);

        $obj_name = $remove -> objecttype -> name;
        $obj_id = $remove -> object_id;

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $obj_id)->first();
        
        $object->update(['status_id' => 1]);

        $remove->update(['reason' => "Te állítottad helyre!"]);

        return redirect()->to('/');
    }

    public function remove_restore(Request $data, $id)
    {
        $data->validate([
            'appeal' => ['string', 'min:10', 'max:255'],
        ]);

        $remove = self::item($id);

        $remove->update([
            'appeal' => $data['appeal'],
            'appeal_status' => 2,
            'deletion_time' => Carbon::now()->addDays(8),
        ]);

        return redirect()->to('/');
    }

    public function active_restore(Request $data, $id)
    {
        $data->validate([]);

        $remove = self::item($id);

        $remove->delete();

        return redirect()->to('/');
    }

    public function answer_appeal(Request $data, $id)
    {
        $data->validate([]);

        $remove = self::item($id);
        $obj_name = $remove -> objecttype -> name;
        $obj_id = $remove -> object_id;

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $obj_id)->first();

        if ($data->submitbutton == "accept")
        {
            $remove->update([
            'appeal_status' => 4,
            'deletion_time' => Carbon::now()->addDays(8),
            'reason' => "Elfogadták a fellebbezésed!"
                    ]);

            
            $object->update(['status_id' => 1]);
        }

        if ($data->submitbutton == "reject")
        {
            $remove->update([
            'appeal_status' => 3,
            'deletion_time' => Carbon::now()->addDays(8),
                    ]);
        } 

        return redirect()->to('/');
    }

}