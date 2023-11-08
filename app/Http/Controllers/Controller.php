<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Remove;
use App\Models\Right;
use App\Models\Group;
use App\Models\Group_user;
use App\Models\Role;
use App\Models\Objecttype;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use function PHPUnit\Framework\returnSelf;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function auth($prop)
    {
        if (Auth()->user()) {
            return Auth()->user()->{$prop};
        } else {
            return null;
        }
    }

    //Igaz, ha az aktív felhasználó be van jelentkezve.
    public function isLogged()
    {
        if ($this->auth('id') == null) return false;

        return true;
    }

    //Igaz, ha az aktív felhasználó nincs törölve vagy tiltva.
    public function isActive()
    {      
        if ($this->auth('status_id') == 1) return true;
        if ($this->isAdmin()) return true;

        return false;
    }

    //Igaz, ha a bejelentkezett felhasználó = a paraméterben megadott user, vagy ő az admin.
    public function isOwner($id)
    {
        if ($this->auth('id') == $id) return true;
        if ($this->isAdmin()) return true;

        return false;
    }

    //Igaz, ha a megadott "remove"-ban szereplő tartalom a
    //bejelentkezett felhasználóhoz köthető, vagy ő az admin.
    public function isDefendant($id)
    {  
        $remove = Remove::where('id', $id) -> first();

        if ($remove == null) return false;

        $user_id = null;
        $obj_name = $remove -> objecttype -> name;
        $obj_id = $remove -> object_id;
        $model = 'App\Models\\'.$obj_name;

        $object = $model::where('id', $obj_id) -> first();

        if ($object == null) $user_id = null;
        elseif ($obj_name == "user") $user_id = $object -> id;
        else $user_id = $object -> user_id;

        if ($this->auth('id') == $user_id) return true;
        if ($this->isAdmin()) return true;

        return false;
    }

    //Igaz, ha a megadott "remove"-ban szereplő tartalom felett rendelkezhet a
    //bejelentkezett felhasználó
    public function isClaimant($id)
    {
        $remove = Remove::where('id', $id) -> first();

        if ($remove == null) return false;
        
        $obj_name = $remove -> objecttype -> name;
        $obj_id = $remove -> object_id;
        $model = 'App\Models\\'.$obj_name;

        $object = $model::where('id', $obj_id) -> first();

        $canReview = $this->canReview($object);

        if ($obj_name == "user" || $obj_name == "group") $canReview = false;

        if ($canReview) return true;
        if ($this->isAdmin()) return true;

        return false;
    }

    //visszaadja, hogy a bejelentkezett felhasználó megnézheti-e a fellebbezést
    public function canReview($object)
    {
        if ($object == null) return false;
        elseif (class_basename($object) == 'Comment')
        return $this->hasRight('review_appeals group', $object -> post -> group_id);
        elseif (class_basename($object) == 'Group')
        return $this->hasRight('review_appeals group', $object -> id);
        else return $this->hasRight('review_appeals group', $object -> group_id);
    }
    public function isAdmin()
    {
        if ($this->auth('role_id') == 1) return true;

        return false;
    }

    /*
    igaz, ha a bejelentkezett felhasználó a tulajdonosa a megadott csoportnak,
    vagy olyan szerepkörrel rendelkezik, aminek van joga a megadott művelet használatához.
    right = a végrehajtandó művelet neve
    obj_id = az objektum, amit szerkeszteni akarunk
    */
    public function hasRight($right, $obj_id)
    {
        $right_name = explode(' ', $right)[0];
        $obj_name = explode(' ', $right)[1];
        $obj_type = Objecttype::where('name', $obj_name)->first()->id;

        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $obj_id)->first();

        if ($object == null) return false;

        $group_id = $this->getGroup_id($object);

        $member = $this->member($group_id);

        if ($obj_name == 'group') $other_member = null;
        else $other_member = $this -> other_member($object);

        $hasPriority = $this->hasPriority($member, $other_member);

        $isRightExists = $this->isRightExists($member, $right_name, $obj_type);

        $isPostOwner = $this->isPost_owner($object);
        
        $isOwner = $this->isObjectOwner("group", $group_id);

        $isActive = ($member != null) && ($member -> status -> name == 'active');

        if ($isRightExists && $hasPriority && $isActive) return true;
        if ($isPostOwner && $isActive) return true; //poszt tulajdonos bárki kommentjét törölheti.
        if ($isOwner) return true; //csoport tulajdonosnak mindíg legyen jogosultsága bármihez.
        if ($this->isAdmin()) return true;

        return false;
    }

    //visszaadja a bejelentkezett felhasználó tagságát a megadott csoportban.
    public function member($group_id)
    {
        return Group_user::where('group_id', $group_id)
        ->where('user_id', $this->auth('id'))->first();
    }

    //visszaadja annak a felhasználónak tagságát, akihez köthető a megadott object.
    public function other_member($object)
    {
        return Group_user::where('group_id', $object -> group_id)
        ->where('user_id', $object -> user_id)->first();
    }
    
    //visszaadja, hogy az első paraméterben megadott tagnak van-e hatalma a
    //második paraméterben megadott tag felett.
    public function hasPriority($member, $other_member)
    {
        if ($member == null) return false;
        if ($other_member == null) return true;

        if ($other_member == $member) return false;
        if ($other_member -> group -> user_id == $other_member -> user_id) return false;

        if ($member == null) $role = null;
        else $role = Role::where('id', $member -> role_id)->first();

        if ($other_member == null) $other_role = null;
        else $other_role = Role::where('id', $other_member -> role_id)->first(); 

        if ($role == null) return false;
        if ($other_role == null) return true;
        
        return $role -> priority > $other_role -> priority;
    }

//visszaadja, hogy a megadott szerepkörnek van e joga a megadott művelet végrehajtásához.
    public function isRightExists($member, $right_name, $obj_type)
    {
        if ($member == null) $role = null;
        else $role = Role::where('id', $member -> role_id)->first();

        if ($role == null) return false;
        else return Right::where('role_id', $role->id)->where('right_name', $right_name)
        ->where('object_type', $obj_type)->first() != null;
    }

    //visszaadja, hogy a megadott objektum melyik csoportban lett megosztva.
    public function getGroup_id($object)
    {
        if (class_basename($object) == 'Group') return $object -> id;
        if (class_basename($object) == 'Comment') {
            if ($object -> post != null) return $object -> post -> group_id;
            else return false;
        }
        return $object -> group_id;
    }

    //visszaadja, hogy a megadott object (komment)
    //a bejelenkezett felhasználó posztja alá lett-e írva.
    //ha nem komment a megadott objektum, akkor FALSE.
    public function isPost_owner($object)
    {
        if (class_basename($object) != "Comment") return false;
        else if ($object -> post != null)
            return $object -> post -> user_id == $this->auth('id');
        else return false;
    }

    //visszaadja, hogy a megadott objektum a bejelentkezett felhasználóé-e.
    public function isObjectOwner($obj_name, $obj_id)
    {
        $model = 'App\Models\\'.$obj_name;
        $object = $model::where('id', $obj_id)->first();

        if ($object == null) return false;

        return $object -> user_id == $this->auth('id');
    }
}
