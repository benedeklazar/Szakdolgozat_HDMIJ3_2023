<?php
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