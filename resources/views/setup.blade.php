<?php use App\Models\User;

//ha nincs admin, akkor az első aktív felhasználót adminná teszi.
if (User::where('role_id', 1)->count() == 0 && User::where('status_id', 1)->count() > 0)
{
    $id = User::where('status_id', 1)->first() -> id;
 
    return User::where('id', $id)->update([
            'role_id' => 1,
        ]);
}
?>