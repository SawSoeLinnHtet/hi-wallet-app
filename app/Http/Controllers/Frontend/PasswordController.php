<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\GeneralNotification;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Notification;

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

            $title = 'Changed Password';
            $message = 'Your account password is successfully changed!';
            $sourceable_id = $user->id;
            $sourceable_type = User::class;
            $web_link = route('profile');

            Notification::send([$user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));

            return redirect()->route('profile')->with('update', 'Successfully updated');
        }

        return back()->withErrors(['old_password' => 'The old password is not correct.'])->withInput();
    }
}
