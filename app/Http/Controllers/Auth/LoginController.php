<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\UserService\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;


class LoginController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function Login(Request $request)
    {
        $getAppUrl = $this->getAppUrl();

       if ($request->url() == $getAppUrl . '/loginAdmin') {

            $user = User::where('email', $request->email)->first();

            if (!$user || !password_verify($request->password, $user->password)) {
                return back()->withErrors([
                    'email' => 'Credenciais invalidas.',
                ])->onlyInput('email');
            }
                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ]);

                if (! $token = Auth::attempt($credentials)) {

                    $request->session()->regenerate();
                    $request->session()->put($credentials);

                    return response()->json(['email' => $request->email], 200);
                }
                else{
                    return back()->withErrors([
                        'password' => 'Senha não encontrada nos nossos registros.',
                    ])->onlyInput('email');
                }
                return $this->respondWithToken($token);
            

        } else {
            $customer = Customer::where('email', $request->email)->first();

            if (!$customer || !password_verify($request->password, $customer->password)) {
              
                return back()->withErrors([
                    'email' => 'Credenciais invalidas.',
                ])->onlyInput('email');
            }
                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ]);

                if (!$token = Auth::guard('customer')->attempt($credentials)) {

                    $request->session('customer')->regenerate();
                    $request->session('customer')->put($credentials);
                    return response()->json(['email' => $request->email, 200]);
                }
                else{
                    return back()->withErrors([
                        'password' => 'Senha não encontrada',
                    ])->onlyInput('email');
                }
                    return $this->respondWithToken($token);
        }
    }
    public function getAppUrl()
    {
        $url = env('APP_URL') . ':8000';
        return $url;
    }
    public function register(Request $request)
    {
        try {
            $store = $this->userService->store($request);
            if($store){
                return redirect('/admin');
            }
            return response()->json($store);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
