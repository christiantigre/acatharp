<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use JWTAuthException;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class UserController extends Controller
{
    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }

    public function login(Request $request)
    {
       
        
        $credentials = $request->only('email', 'password');
        $user = \App\User::where('email', $request->email)->get()->first();

        if(isset($user)){
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => true,'data' => 'invalid_credentials no_exist'], 400);
                }
            } catch (JWTException $e) {
                  return response()->json(['error' => true, 'data' => 'invalid_credentials'], 500);
            }
        }else{
            $login = $this->loginRp($request->email,  base64_encode($request->password) );
            $usuario = $login['auto']['usuario'];
            $if_exist = \App\User::where('email',$login['auto']['usuario']['email'])->first();
            
                if(isset( $if_exist )){                    
                    $registrar = \App\User::findOrFail($login['auto']['usuario']['email']);
                    $registrar->update([
                        'name' => $registrar['auto']['nombres'],
                        'password' => \Hash::make($registrar['auto']['password'])
                    ]);
                    $token = JWTAuth::fromUser($registrar);
                }else{
                    $registrar = \App\User::create([
                        'name' => $usuario['nombres'],
                        'email' => $usuario['email'],
                        'password' => \Hash::make($request->password),
                        'auth_token'=> ''
                    ]);
                    $token = JWTAuth::fromUser($registrar);
                }

        }
        

        return response()->json( ['error'=>false, 'token'=>$token] );

    }

    public function register(Request $request)
    { 

        $validator = Validator::make($request->all(), [ 
            'name' => 'required|alpha_num|min:3|max:32',
            'email' => 'required|email',
            'password' => 'required|min:3|confirmed',  
            'password_confirmation' => 'required|same:password', 
        ]); 

        $payload = [
            'password'=>\Hash::make($request->password),
            'email'=>$request->email,
            'name'=>$request->name,
            'auth_token'=> ''
        ];

        if ($validator->fails()) {          
            return response()->json(['error'=>true,'data'=>$validator->errors()], 401);                        
        }    
     
                  
        $user = new \App\User($payload);
        try{

            if ($user->save())
            {            
                $token = JWTAuth::fromUser($user);
                
                $response = ['error'=>true, 'data'=>['name'=>$user->name,'id'=>$user->id,'email'=>$request->email,'auth_token'=>$token]];        
            }
            else
                $response = ['error'=>false, 'data'=>'Couldnt register user'];

        } catch (\Exception $e) {
            
            $response = ['error'=>true, 'data'=>'Couldnt register user'];
            
        }                
        
        return response()->json($response, 201);
    }

    public function loginRp($user, $password){
        $client = new Client();
            $url = 'http://prd.acatha.com/amfphp/services/SIGNUM/API/v2/login/autenticar';

            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => '15y580dvCzHIM',
                    'CLIENT_ID' => '8434152794',
                    'SECRET_KEY' => 'f?2%Am}{$OiJAtZj*gDv3sTS84qDY3',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'user'=>$user,
                    'pass'=>$password,
                    'platform'=>'web',
                ]       
            ]);
            $response = $response->getBody()->getContents();
            $result = json_decode($response, true);

            return $result;
    }

}
