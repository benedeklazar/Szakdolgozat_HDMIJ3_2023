public function auth($prop)
    {
        if (Auth()->user()) {
            return Auth()->user()->{$prop};
        } else {
            return null;
        }
    }