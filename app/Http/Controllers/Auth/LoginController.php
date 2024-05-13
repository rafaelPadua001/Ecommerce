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

        //recebe a url de onde vem a requisicao
        $getAppUrl = $this->getAppUrl();

        //se a requisicao for do form admin
        // deverá fazer o login com as credentials
        // e redirecionar para a dashboard

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

                    // return redirect()->intended('dashboard');
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

            if (!$customer || !password_verify($request->password, $customer->password, $customer->password)) {
              
                return back()->withErrors([
                    'email' => 'Credenciais invalidas.',
                ])->onlyInput('email');
            }
                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ]);

                if (! $token = Auth::guard('customer')->attempt($credentials)) {

                    $request->session('customer')->regenerate();
                    //$request->session('customer')->put($customer);
                    //return redirect()->intended('dashboard');
                    // Redireciona o usuário para a página inicial
                    return response()->json(['email' => $request->email, 200]);
                }
                    return $this->respondWithToken($token);
            

            // return back()->withErrors([
            //     'email' => 'Email não encontrado nos nossos registros.',
            // ])->onlyInput('email');
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
