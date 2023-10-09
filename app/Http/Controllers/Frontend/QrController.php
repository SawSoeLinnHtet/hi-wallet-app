<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QrController extends Controller
{
    public function reveive()
    {
        $auth_user = Auth::user();
        return view('frontend.qrcode.receive', ['auth_user' => $auth_user]);
    }

    public function scanAndPay()
    {
        return view('frontend.qrcode.scan_and_pay');
    }

    public function form(Request $request)
    {
        $to_phone = User::where('phone', $request->to_phone)->first();

        if (!$to_phone) {
            return back()->withErrors(['fail' => 'OR code is invalid.'])->withInput();
        }

        return view('frontend.wallet.transfer', ['to_phone' => $to_phone]);
    }
}
