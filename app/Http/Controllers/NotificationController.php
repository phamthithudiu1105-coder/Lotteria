<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get unread notifications count
    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = DB::table('notifications')
            ->where('MaTaiKhoan', Auth::user()->MaTaiKhoan)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    // Get all notifications for current user
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $notifications = DB::table('notifications')
            ->where('MaTaiKhoan', Auth::user()->MaTaiKhoan)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 403);
        }

        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('MaTaiKhoan', Auth::user()->MaTaiKhoan)
            ->first();

        if ($notification) {
            DB::table('notifications')
                ->where('id', $id)
                ->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 403);
        }

        DB::table('notifications')
            ->where('MaTaiKhoan', Auth::user()->MaTaiKhoan)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // View notification detail (for rejected ones)
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('MaTaiKhoan', Auth::user()->MaTaiKhoan)
            ->first();

        if (!$notification) {
            return redirect()->back()->with('error', 'Thông báo không tồn tại');
        }

        // Mark as read
        DB::table('notifications')
            ->where('id', $id)
            ->update(['is_read' => true]);

        $data = json_decode($notification->data, true);

        if ($notification->type === 'kiemke_rejected') {
            return redirect()->route('kiemke.bep');
        }

        if ($notification->type === 'kiemke_pending') {
            return redirect()->route('quanly.kiemke.bep');
        }

        if ($notification->type === 'kiemke_periodic_pending') {
            return redirect()->route('quanly.khochinh.duyet');
        }

        if ($notification->type === 'xuatkho_pending') {
            return redirect()->route('nhanvien.phieuxuat');
        }

        if ($notification->type === 'xuatkho_completed') {
            return redirect()->route('xuatkho.index');
        }

        if ($notification->type === 'kiemke_stats_available') {
            return redirect()->route('cht.khochinh.thongke');
        }

        if (in_array($notification->type, ['donhang_approved', 'donhang_rejected', 'donhang_waiting', 'donhang_completed'])) {
            return redirect()->route('don-hang.show', $data['MaDonDatHang']);
        }

        return redirect()->back();
    }
}
