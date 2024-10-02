<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // $userId = $request->user()->id; 

        // Lấy tất cả thông báo của người dùng theo thứ tự thời gian mới nhất
        // $notifications = Notification::where('user_id', $userId)
        //                             ->orderBy('created_at', 'desc')
        //                             ->get();

        $notifications = Notification::select('id', 'title', 'message')
            ->get();
        // return Inertia::render('Notification/Index', [
        //     'notifications' => $notifications
        // ]);  
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
