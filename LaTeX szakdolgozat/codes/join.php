public function join($id)
    {
        if(!(
            $this->isLogged() &&
            $this->isActive()
        ))return redirect()->back();

        $current_membership = Group_user::where('group_id', $id)
        ->where('user_id', $this->auth('id'))->first();

        $group = Group::where('id', $id) -> first();

        $new_member_status = $group -> new_member_status_id;
        
        if ($current_membership != null)
        return redirect()->back();

        if ($new_member_status == null)
        return redirect()->back();

        if ($group -> visibility -> name == "private")
        return redirect()->back();

        Group_user::create([
            'group_id' => $id,
            'user_id' => $this->auth('id'),
            'role_id' => 3,
            'status_id' => $new_member_status
        ]);

        return redirect()->to('/group/'.$id);
    }