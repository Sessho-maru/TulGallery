<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    protected function validateUser()
    {
        return request()->validate([
            'user_name' => 'required',
            'reset_token' => 'required|numeric|digits:6'
        ]);
    }

    protected function validatePassword()
    {
        /*
        return request()->validate([
            'password' => 'required|confirmed'
        ]);
        */
        // It didn't work...
        
        if (strlen(request()->password) < 6)
        {
            return back()->withErrors(['password' => 'Password must be longer than 5 characters']);
        }

        if (request()->password != request()->password_confirm)
        {
            return back()->withErrors(['password' => 'Password confirmation doesn\'t match']);
        }
    }

    public function check()
    {
        $validated = $this->validateUser();

        $user = User::where('user_name',request()->user_name)->first();

        if ($user != null)
        {
            if ($user->password_reset_token == $validated['reset_token'])
            {      
                return view('reset')->with('user_id', $user->id);
            }
            return back()->withErrors(['reset_token' => 'Given token isn\'t correct']);
        }

        return back()->withErrors(['user_name' => 'There isn\'t such user']);
    }

    public function reset()
    {
        $this->validatePassword();

        User::find(request()->user_id)->update([
            'password' => Hash::make(request()->password)
        ]);
        
        return redirect()->route('login');
    }
}