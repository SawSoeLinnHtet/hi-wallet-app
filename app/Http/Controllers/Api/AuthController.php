<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $data = $request->validated();

        $data['ip'] = $request->ip();
        $data['user_agent'] = $request->server('HTTP_USER_AGENT');
        $data['login_at'] = Carbon::now();

        $user = User::create($data);

        Wallet::firstOrCreate(
            ['user_id' =>  $user->id],
            [
                'account_number' => UUIDGenerate::accountNumber(),
                'amount' => 0,
            ]
        );

        $token = $user->createToken('HI Wallet')->accessToken;

        return ResponseHelper::success('Successfully Registered', ['token' => $token]);
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'phone' => ['required', 'string'],
                'password' => ['required', 'string']
            ]
        );

        $auth_check = Auth::attempt(['phone' => $request->phone, 'password' => $request->password]);

        if($auth_check){
            $user = Auth::User();

            $user->ip = $request->ip();
            $user->user_agent = $request->server('HTTP_USER_AGENT');
            $user->login_at = Carbon::now();
            $user->update();

            Wallet::firstOrCreate(
                ['user_id' =>  $user->id],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0,
                ]
            );

            $token = $user->createToken('HI Wallet')->accessToken;

            return ResponseHelper::success('Successfully Login', ['token' => $token]);
        }

        return ResponseHelper::fail('The credentials does not match our record.', null);
    }

    public function logout()
    {
        $user = auth()->user();

        $user->token()->revoke();

        return ResponseHelper::success('Successfully logged out', null);
    }
}
