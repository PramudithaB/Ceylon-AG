<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefNotificationController extends Controller
{
    /**
     * Display list of notifications.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        return view('ref.notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        flash_message('All notifications marked as read.', 'success');

        return redirect()->back();
    }
}
