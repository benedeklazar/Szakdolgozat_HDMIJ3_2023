protected function validator(array $data)
    {
        return Validator::make($data, [
            
            'username' => ['required', 'string',
                'min:5', 'max:255', 'unique:users'],

            'password' => ['required', 'string',
                'min:5', 'confirmed'],
        ]);
    }