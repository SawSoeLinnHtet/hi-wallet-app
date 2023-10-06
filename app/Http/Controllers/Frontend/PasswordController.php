<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function index()
    {
        return view('frontend.update_password');
    }

    public function update(UpdatePasswordRequest $request)
    {
        $old_password = $request->old_password;
        $new_password = $request->new_password;

        $user = Auth::user();

        if (Hash::check($old_password, $user->password)) {
            $user->password = $new_password;
            $user->update();

            return redirect()->route('profile')->with('update', 'Successfully updated');
        }

        return back()->withErrors(['old_password' => 'The old password is not correct.'])->withInput();
    }
}
