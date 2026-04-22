<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── 4 KPI widgets ──────────────────────────────────────────
        $revenue = Order::where('status', 'delivered')->sum('total');

        $todayOrders = Order::whereDate('created_at', today())->count();

        $totalOrders = Order::count();

        $outOfStock = ProductVariant::where('stock', 0)->count();

        // ── Last 10 orders ─────────────────────────────────────────
        $recentOrders = Order::with('wilaya')
            ->latest()
            ->take(10)
            ->get();

        // ── Top 5 best-selling products ────────────────────────────
        $topProducts = OrderItem::select(
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // ── Sales chart — last 30 days (includes last 7) ───────────
        $chartRows = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as nb_orders')
            )
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build arrays for last 7 and last 30 days
        $chart7  = $this->buildChartData($chartRows, 7);
        $chart30 = $this->buildChartData($chartRows, 30);

        // ── Pending orders count (badge) ───────────────────────────
        $pendingCount = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'revenue',
            'todayOrders',
            'totalOrders',
            'outOfStock',
            'recentOrders',
            'topProducts',
            'chart7',
            'chart30',
            'pendingCount',
        ));
    }

    private function buildChartData($rows, int $days): array
    {
        $labels  = [];
        $revenues = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[]   = now()->subDays($i)->format('d/m');
            $revenues[] = (float) ($rows[$date]->revenue ?? 0);
        }

        return ['labels' => $labels, 'revenues' => $revenues];
    }
}
