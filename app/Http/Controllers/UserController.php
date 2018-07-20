<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;

class UserController extends Controller
{
	public function show()
	{
		if(Auth::check())
		{
			$users = User::all();

			return view('users.registration', compact(
				'users'
			));
		}
		else
		{
		 return redirect()->route('login');
		}
	}

	public function create()
    {
    	$this->validate(request(), [
			'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
		]);


        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);

        $users = User::all();

        return view('users.registration', compact(
        	'users'
        ));
    }

    public function edit()
    {
        return view('users.edit');
    }

    public function update()
    {
    	$this->validate(request(), [
            'password' => 'required|string|min:6|confirmed',
		]);


		$users = User::find(Auth::user()->id);
		$users->password = bcrypt(request('password'));
		$users->save();

		Session::flash('flash_message','Successfully Changed.');	

    	return view('users.edit');
    }
}
