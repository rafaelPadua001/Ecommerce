<?php 
    namespace App\Http\Controllers\Auth;

   use App\Models\User;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    

    class LoginController extends Controller{
        
        public function Login(Request $request){
            
            //recebe a url de onde vem a requisicao
            $getAppUrl = $this->getAppUrl();
            
            //se a requisicao for do form admin
            // deverá fazer o login com as credentials
            // e redirecionar para a dashboard
            
            if($request->url() == $getAppUrl . '/loginAdmin'){
                $user = User::where('email', $request->email)->first();
        
                if(password_verify($request->password, $user->password)){
                    $credentials = $request->validate([
                        'email' => ['required', 'email'],
                        'password' => ['required'],
                ]);
                
                    if(Auth::attempt($credentials)){
                        $request->session()->regenerate();
                        $request->session()->put($credentials);
                        
                        return redirect()->intended('dashboard');
                }
                return back()->withErrors([
                    'password' => 'Senha não encontrada nos nossos registros.',
               ])->onlyInput('email');

             }
               
               return back()->withErrors([
                    'email' => 'Email não encontrado nos nossos registros.',
               ])->onlyInput('email');
            }
            else{
                
                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required'],
               ]);
                if(Auth::attempt($credentials)){
                    $request->session('customer')->regenerate();
                    
                    return redirect()->intended('customerDashboard');
                } 

                return back()->withErrors([
                    'email' => 'Email não encontrado nos nossos registros.',
                ])->onlyInput('email');
            }
          
        }
        public function getAppUrl(){
            $url = env('APP_URL') . ':8000';
            return $url;
        }
    }