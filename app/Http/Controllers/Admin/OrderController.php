<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Shipment;
use App\Models\Wilaya;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['wilaya'])->latest();

        // Recherche
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        // Filtre statut
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filtre wilaya
        if ($wilayaId = $request->input('wilaya_id')) {
            $query->where('wilaya_id', $wilayaId);
        }

        // Filtre date
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Filtre méthode de paiement
        if ($payment = $request->input('payment_method')) {
            $query->where('payment_method', $payment);
        }

        // Tri
        $sortBy  = in_array($request->input('sort'), ['created_at', 'total', 'status']) ? $request->input('sort') : 'created_at';
        $sortDir = $request->input('dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $orders  = $query->paginate(20)->withQueryString();
        $wilayas = Wilaya::orderBy('code')->get();

        // Compteurs par statut
        $counts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.orders.index', compact('orders', 'wilayas', 'counts'));
    }

    public function show(Order $order): View
    {
        $order->load([
            'wilaya',
            'items.variant.product.images',
            'shipment',
            'statusHistory',
            'promoCode',
            'user',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,shipped,delivered,cancelled'],
        ]);

        $newStatus = $request->input('status');

        if ($newStatus === $order->status) {
            return back()->with('info', 'Le statut est déjà ' . $order->status_label . '.');
        }

        // Historique
        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'from_status' => $order->status,
            'to_status'   => $newStatus,
            'note'        => $request->input('note'),
            'changed_at'  => now(),
        ]);

        // Timestamps spécifiques
        $update = ['status' => $newStatus];

        switch ($newStatus) {
            case 'confirmed': $update['confirmed_at'] = now(); break;
            case 'shipped':   $update['shipped_at']   = now(); break;
            case 'delivered': $update['delivered_at'] = now(); break;
            case 'cancelled':
                $update['cancelled_at'] = now();
                // Restaurer le stock
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
                break;
        }

        $order->update($update);

        // Créer ou mettre à jour l'entrée shipment si "shipped"
        if ($newStatus === 'shipped') {
            Shipment::firstOrCreate(
                ['order_id' => $order->id],
                ['status' => 'shipped', 'shipped_at' => now()]
            );
        }

        return back()->with('success', 'Statut mis à jour : ' . Order::STATUSES[$newStatus]['label'] . '.');
    }

    public function updateShipment(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'carrier'         => ['nullable', 'string', 'max:100'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'carrier'         => $request->input('carrier'),
                'tracking_number' => $request->input('tracking_number'),
                'status'          => 'shipped',
                'shipped_at'      => $order->shipped_at ?? now(),
            ]
        );

        return back()->with('success', 'Informations de livraison enregistrées.');
    }

    public function updateNote(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update(['notes' => $request->input('notes')]);

        return back()->with('success', 'Note interne sauvegardée.');
    }

    public function export(Request $request): Response|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        $query = Order::with(['wilaya'])->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($wilayaId = $request->input('wilaya_id')) {
            $query->where('wilaya_id', $wilayaId);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        if ($payment = $request->input('payment_method')) {
            $query->where('payment_method', $payment);
        }

        $orders = $query->get();

        $filename = 'commandes-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['#', 'Client', 'Téléphone', 'Wilaya', 'Total (DA)', 'Statut', 'Paiement', 'Date'], ';');

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->wilaya->name ?? '',
                    number_format((float) $order->total, 2, '.', ''),
                    $order->status_label,
                    $order->payment_method_label,
                    $order->created_at?->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
