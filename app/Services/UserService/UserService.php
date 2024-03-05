<?php

namespace App\Services\UserService;

use App\Mail\MailRegister;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService {
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }
    public function store($request){
       try{
            // $request->validate([
            //     'name' => 'required|string|max:255',
            //     'email' => 'required|string|email|max:255|unique:'.User::class,
            //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // ]);

            $user = User::create([
                'name' => $request->name . '' . $request->first_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $sendMail = Mail::to($request->email)->send(new MailRegister($request->first_name, $request->last_name));
            
            event(new Registered($user));

          //  Auth::login($user);

            return $user;
       }
       catch(Exception $e){
        return response()->json($e->getMessage());
       }
    }
}