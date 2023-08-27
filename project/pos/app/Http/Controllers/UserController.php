<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function LoginPage():View{
        return view('pages.auth.login-page');
    }
    function RegistrationPage():View{
        return view('pages.auth.registration-page');
    }
    function SendOtpPage():View{
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage():View{
        return view('pages.auth.verify-otp-page');
    }
    function ResetPasswordPage():View{
        return view('pages.auth.reset-pass-page');
    }
    //profile page
    function ProfilePage():View{
        return view('pages.dashboard.profile-page');
    }





    function UserRegistration(Request $request){
        try{
            User::create([
                'firstName'=>$request->input('firstName'),
                'lastName'=>$request->input('lastName'),
                'email'=>$request->input('email'),
                'mobile'=>$request->input('mobile'),
                'password'=>$request->input('password'),

            ]);

            return response()->json([
                'status'=>'success',
                'message'=>'User Registration successfull'
            ],'200');
        }
        catch(Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'User Registration failed'
            ],'401');
        }
    }

    function UserLogin(Request $request){
        $count=User::where('email','=',$request->input('email'))
             ->where('password','=',$request->input('password'))
             ->select('id')->first();

        if($count!==null){
            // User Login-> JWT Token Issue
            $token=JWTToken::CreateToken($request->input('email'),$count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
            ],200)->cookie('token',$token,60*24*30);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);

        }

     }


    function SendOTPCode(Request $request){
        $email= $request->input('email');
        $otp = rand(1000,9999);
        $count= User::where('email','=',$request->input('email'))
        ->count() ;


        if($count==1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status'=>'success',
                'message'=>'4 disit otp code has been send to your email !',

            ],'200');
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'Unauthorize'
            ],'401');
        }

    }
    function VerifyOTP(Request $request){
        $email= $request->input('email');
        $otp = $request->input('otp');
        $count=User::where('email','=',$email)->where('otp','=',$otp)
        ->count() ;


        if($count==1){
            //database OTP Update

            User::where('email','=',$email)->update(['otp'=>'0']);

            //password reset token issue
            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));

            return response()->json([
                'status'=>'success',
                'message'=>'OTP verification successful',
                // 'token'=>$token

            ],'200')->cookie('token',$token,60*60*24);;
        }
        else{
            return response()->json([
                'status'=>'failed',
                'message'=>'Unauthorize'
            ],'401');
        }

    }

    function ResetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status'=>'success',
                'message'=>'Request successful',


            ],'200');
        }
        catch(Exception $exception){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong'
            ],'401');

        }

    }
//user profile method
    function UserProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ],200);
    }


    //log out
    function UserLogout(){
        return redirect('/userLogin')->cookie('token','',-1);
    }


    function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }



}
