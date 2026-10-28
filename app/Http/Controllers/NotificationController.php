<?php

namespace App\Http\Controllers;

use App\Models\ContractNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = ContractNotification::with(['contract.client'])
            ->whereHas('contract')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter berdasarkan tipe
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->paginate(15);

        // Hitung statistik
        $unreadCount = ContractNotification::where('is_read', false)->count();
        $reminderCount = ContractNotification::whereIn('type', [ContractNotification::TYPE_EXPIRY, ContractNotification::TYPE_RENEWAL])->count();
        $criticalCount = ContractNotification::whereIn('type', [ContractNotification::TYPE_GRACE_PERIOD, ContractNotification::TYPE_STATUS_CHANGE])->count();

        return view('notifications.index', compact(
            'notifications',
            'unreadCount',
            'reminderCount',
            'criticalCount'
        ));
    }

    public function markRead(ContractNotification $notification)
    {
        $notification->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllRead()
    {
        ContractNotification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    public function clearRead()
    {
        ContractNotification::where('is_read', true)->delete();

        return back()->with('success', 'Notifikasi yang sudah dibaca telah dihapus.');
    }

    public function details(ContractNotification $notification)
    {
        $notification->load(['contract.client', 'contract.creator']);

        $html = view('notifications.partials.detail-modal', compact('notification'))->render();
        $contractUrl = $notification->contract
            ? route('contracts.show', $notification->contract)
            : null;

        return response()->json([
            'success' => true,
            'html' => $html,
            'contract_url' => $contractUrl,
        ]);
    }

    /**
     * Return status/details for AJAX polling
     */
    public function status(ContractNotification $notification)
    {
        $notification->load(['contract']);

        return response()->json([
            'success' => true,
            'id' => $notification->id,
            'message' => $notification->message,
            'sent_date' => $notification->sent_date ? $notification->sent_date->toDateTimeString() : null,
            'data' => $notification->data,
            'created_at' => $notification->created_at->toDateTimeString(),
        ]);
    }

    public function destroy(ContractNotification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
