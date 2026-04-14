<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ClientAdminController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('q', '');
        $sort    = $request->input('sort', 'created_at');
        $dir     = $request->input('dir', 'desc');

        $allowed = ['name', 'created_at', 'orders_count'];
        if (! in_array($sort, $allowed)) $sort = 'created_at';
        if (! in_array($dir, ['asc', 'desc'])) $dir = 'desc';

        $clients = User::where('role', 'client')
            ->with('wilaya')
            ->withCount('orders')
            ->addSelect([
                'total_spent' => Order::selectRaw('COALESCE(SUM(total), 0)')
                    ->whereColumn('user_id', 'users.id')
                    ->where('status', 'delivered'),
                'last_order_at' => Order::select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->limit(1),
            ])
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('name',  'like', "%{$search}%")
                   ->orWhere('phone', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view('admin.clients.index', compact('clients', 'search', 'sort', 'dir'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'client', 404);

        $user->load('wilaya');
        $orders = $user->orders()->with('wilaya')->latest()->paginate(15);

        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent'  => (float) $user->orders()->where('status', 'delivered')->sum('total'),
            'avg_order'    => (float) ($user->orders()->where('status', 'delivered')->avg('total') ?? 0),
            'last_order'   => $user->orders()->latest()->first()?->created_at,
        ];

        return view('admin.clients.show', compact('user', 'orders', 'stats'));
    }
}
