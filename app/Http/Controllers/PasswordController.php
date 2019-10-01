<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    private function validateUser()
    {
        return request()->validate([
            'user_name' => 'required',
            'reset_token' => 'required|numeric|digits:6'
        ]);
    }

    private function validatePassword()
    {
        return request()->validate([
            'password' => 'required|confirmed'
        ]);
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
        $validated = $this->validatePassword();

        User::find(request()->user_id)->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return redirect()->route('login');
    }
}