<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, ForgetPassword};
use Illuminate\Validation\ValidationException;
use App\Http\Resources\{LoginResource};
use Hash, Auth;
use Mail;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
            Auth::login($user);

            return $this->sendResponse(new LoginResource(Auth::user()), 'User logged in successfully!');
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error',  $e->errors());
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'

            ]);
            $user = User::where('email', $request->input("email"))->first();
            if ($user) {
                if (\Auth::attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {

                    Auth::login($user);

                    return $this->sendResponse(new LoginResource(Auth::user()), 'User logged in successfully!');
                } else {
                    return $this->sendError('Invalid Email or Password.');
                }
            } else {
                return $this->sendError('The user of this email does not exists.');
            }
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error',  $e->errors());
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->sendResponse([],'Logged Out Successfully!');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function sendForgetPassword(Request $request)
    {

        try {
            $request->validate([
                'email' => 'required|exists:users,email'
            ]);
            $user = User::where('email', $request->input('email'))->first();
            $otp = rand('1000', '9999');
            ForgetPassword::updateOrInsert(
                ['user_id' => $user->id],
                ['otp' => $otp, 'status' => 0, 'created_at' => now(), 'updated_at' => now()]
            );
            Mail::to($user->email)->send(new ForgetPasswordMail($user, $otp));

            return $this->sendResponse(['id' => $user->id], 'Please Verify the code.');
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error',  $e->errors());
        }  catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function verifyForgotPasswordOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|exists:users,email',
                'otp' => 'required|numeric|digits:4'
            ]);
            $user = User::where('email', $request->email)->first();

            $otpUser = ForgetPassword::where('user_id', $user->id)->where('status', 0)->first();

            if ($otpUser) {
                if ($otpUser->otp == $request->otp) {
                    $otpUser->update(['status' => 1]);
                } else {
                    return $this->sendError('OTP is not valid');
                }
            } else {
                return $this->sendError('This user does not requested for changing password or has already used request for one time');
            }
            return $this->sendResponse($user, 'OTP is successfully verified');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    public function changeForgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);

            $user = User::where('email', $request->input('email'))->first();
            if ($user) {

                $otpUser = ForgetPassword::where('user_id', $user->id)->where('status', 1)->where('updated_at', '>=', now()->copy()->subMinutes(60))->first();

                if ($otpUser) {
                    $otpUser->delete();
                    $user->update(['password' => Hash::make($request->input('password'))]);
                } else {
                    return $this->sendError('This user is not requested for forget password or otp is expired! Please request again for forget password.');
                }
                return $this->sendResponse($user, 'Password Change Succeesfully');
            } else {
                return $this->sendError('User does not exists.');
            }
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error',  $e->errors());

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
