@extends('layouts.app')

@section('title', 'Mes Commandes')
@section('page-title', 'Mes Commandes')
@section('page-description', 'Historique de vos commandes')

@section('content')
<div class="max-w-4xl mx-auto">

    @if($orders->isEmpty())
    {{-- Empty state --}}
    <div class="text-center py-20">
        <div class="w-24 h-24 mx-auto rounded-3xl bg-[#f2f4f6] flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-[#c4c6d1]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
            </svg>
        </div>
        <h2 class="text-[20px] font-bold text-[#002352] mb-2">Aucune commande</h2>
        <p class="text-[13px] text-[#747780] mb-8">Vous n'avez pas encore passé de commande.</p>
        <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-[#002352] text-white text-[14px] font-semibold px-6 py-3 rounded-xl hover:bg-[#18396e] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5A2.25 2.25 0 0118.75 19.5H5.25A2.25 2.25 0 013 17.25V6.75z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75h18"/>
            </svg>
            Explorer le catalogue
        </a>
    </div>
    @else

    <div class="space-y-3">
        @foreach($orders as $order)
        @php
            $statusColors = [
                'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                'shipped'   => 'bg-violet-50 text-violet-700 border-violet-200',
                'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'cancelled' => 'bg-red-50 text-red-600 border-red-200',
            ];
            $dotColors = [
                'pending'   => 'bg-amber-500',
                'confirmed' => 'bg-blue-500',
                'shipped'   => 'bg-violet-500',
                'delivered' => 'bg-emerald-500',
                'cancelled' => 'bg-red-500',
            ];
            $statusColor = $statusColors[$order->status] ?? 'bg-slate-50 text-slate-600';
            $dotColor    = $dotColors[$order->status] ?? 'bg-slate-400';
        @endphp

        <a href="{{ route('orders.show', $order->order_number) }}"
           class="group bg-white rounded-2xl shadow-[0px_2px_12px_rgba(24,57,110,0.06)] hover:shadow-[0px_6px_24px_rgba(24,57,110,0.12)] p-5 flex items-center gap-5 transition-all">

            {{-- Status icon --}}
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-all
                {{ $order->status === 'delivered' ? 'bg-emerald-100' :
                   ($order->status === 'cancelled' ? 'bg-red-50' :
                   ($order->status === 'shipped'   ? 'bg-violet-50' :
                   ($order->status === 'confirmed' ? 'bg-blue-50' : 'bg-amber-50'))) }}">
                @if($order->status === 'delivered')
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @elseif($order->status === 'shipped')
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                @elseif($order->status === 'cancelled')
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <div>
                        <p class="font-mono font-bold text-[13px] text-[#002352] group-hover:text-[#18396e] transition-colors">
                            {{ $order->order_number }}
                        </p>
                        <p class="text-[11px] text-[#747780] mt-0.5">
                            {{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY [à] HH[h]mm') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusColor }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                        {{ $order->statusLabel }}
                    </span>
                </div>

                <div class="flex items-center gap-4 mt-2 flex-wrap">
                    <p class="text-[12px] text-[#5d5f5f]">
                        {{ $order->items->count() }} article{{ $order->items->count() > 1 ? 's' : '' }}
                    </p>
                    <span class="text-[#c4c6d1]">•</span>
                    <p class="text-[12px] text-[#5d5f5f]">{{ $order->wilaya->name }}</p>
                    <span class="text-[#c4c6d1]">•</span>
                    <p class="text-[13px] font-bold text-[#002352]">{{ number_format($order->total, 0, ',', ' ') }} DA</p>
                </div>
            </div>

            {{-- Arrow --}}
            <svg class="w-4 h-4 text-[#c4c6d1] group-hover:text-[#18396e] transition-colors flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $orders->links() }}
    </div>
    @endif

    @endif

</div>
@endsection
