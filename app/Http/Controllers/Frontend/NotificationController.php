<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::User()->notifications()->paginate(5);

        return view('frontend.notification.index', ['notifications' => $notifications]);
    }

    public function show($id)
    {
        $notification = Auth::User()->notifications->where('id', $id)->firstOrFail();

        $notification->markAsRead();
        return view('frontend.notification.details', ['notification' => $notification]);
    }
}
