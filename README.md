<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Screenshots 

<p align="center">
  <img src="output-img/register.png" width="350" title="hover text">
  <img src="output-img/login.png" width="350" title="hover text">
</p>
## STEPS FOR USE SANCTUM
<pre>
    LARAVEL SANCTUM TOKEN
Step 1: php artisan install:api
Step 2: composer require laravel/sanctum
Step 3: php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
Step 4: php artisan migrate
Step 5: if(At this moment if you want to store your user data in custom table then you have to modify your model and config>auth.php)
Step 5.1=>modify in config>auth.php
 'guards' => [
						 'guards' => [
    							    'web' => [
      								    'driver' => 'session',
           								    'provider' => 'users',
     								   ],
    						    'api' => [
          							  'driver' => 'sanctum',
          							  'provider' => 'users',
       							 ],
 						   ],
			  'providers' => [
        			       'users' => [
          			                'driver' => 'eloquent',
          				  'model' => 'custom_model',
 				],
  			        ],
Step 5.2 =>modify custom_model
 Add=>use Illuminate\Foundation\Auth\User as Authenticatable;
			Use =>HasApiTokens
			Add=> protected $hidden =[
      							  'password','remember_token',
            ];
else{ No need this changes it work default.}
Step 6 : Bootstrap>app.php for exception
 ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exceptions) {
            $returnData = [
                'message' => 'User is not authenticated!',
                'error' => $exceptions->getMessage()
            ];

            if (true) {
                $returnData['debug'] = $exceptions->getMessage();
            }

            return response()->json($returnData, 401);
        });
    })->create();
It will Handle the exception if you use wrong auth token it show error .
Step 7. NOW you can create controller and use registration and login

    Route::POST('register',[AutController::class,'register']);
REGISTER()
 public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:user_details',
            'password' => 'required'

        ]);
        if($validator->fails()){
            return $this->SendError('validation Error Occur',$validator->errors()->all());
        }
        else{
            $user = new userDetails();
            $user->name =$request->name;
            $user->email =$request->email;
            $user->password =Hash::make($request->password);
            $user->save();
            return $this->SendResponse('User Created Successfully.',$user);}}

LOGIN()
<p>Route::POST('login',[AuthController::class,'login'])->name('login');</p>
<pre>
     public function login(Request $request){
        $validator = Validator::make(request()->all(), [
            'email' => 'required' ,
            'password' => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()]);
        }
        $userExist = User::where('email',$request->email)->first();
        if(!$userExist){
            return response()->json(['message' => 'User not found']);
        }
        else if(Auth::attempt(['email' => $userExist->email,'password'=>$request->password])){
            $Authuser =Auth::user()->email;
            $expire_in = 60*60*60;
            $token =$userExist->createToken('MyApp',['expire_in'=>$expire_in])->plainTextToken;
            $success['token']=$token;
            $success['User']=$Authuser;
            $success['expire_in']=$expire_in;
            return response()->json(['message' => 'User logged in successfully',$success]);
        }
    }
</pre>


