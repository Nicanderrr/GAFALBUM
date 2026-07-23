<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $successfulTransactions = Transaction::query()->where('status', 'success');
        $pendingTransactions = Transaction::query()->where('status', 'pending');

        $stats = [
            'revenue' => (float) (clone $successfulTransactions)->sum('amount'),
            'successful_payments' => (clone $successfulTransactions)->count(),
            'pending_payments' => (clone $pendingTransactions)->count(),
            'pending_value' => (float) (clone $pendingTransactions)->sum('amount'),
            'files_sold' => TransactionItem::whereHas('transaction', fn ($query) => $query->where('status', 'success'))->count(),
            'events' => Image::count(),
            'media_files' => ImageMedia::count(),
            'categories' => Category::count(),
            'users' => User::where('is_admin', false)->count(),
            'admins' => User::where('is_admin', true)->count(),
            'cart_items' => CartItem::count(),
            'new_uploads' => Image::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $recentImages = Image::with('category')->latest()->take(5)->get();
        $recentPayments = Transaction::with(['user', 'items'])
            ->latest()
            ->take(6)
            ->get();

        $categorySales = TransactionItem::query()
            ->selectRaw('COALESCE(categories.name, ?) as label, COUNT(transaction_items.id) as files, SUM(transaction_items.amount) as amount', ['Uncategorized'])
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('images', 'images.id', '=', 'transaction_items.image_id')
            ->leftJoin('categories', 'categories.id', '=', 'images.category_id')
            ->where('transactions.status', 'success')
            ->groupByRaw('COALESCE(categories.name, ?)', ['Uncategorized'])
            ->orderByDesc('amount')
            ->get();

        $pieColors = ['#800000', '#a85555', '#c24141', '#f87171', '#fca5a5', '#7f1d1d'];
        $categoryTotal = max((float) $categorySales->sum('amount'), 0);
        $pieStart = 0;
        $pieSegments = $categorySales->values()->map(function ($sale, $index) use ($pieColors, $categoryTotal, &$pieStart) {
            $percentage = $categoryTotal > 0 ? ((float) $sale->amount / $categoryTotal) * 100 : 0;
            $end = $pieStart + (($percentage / 100) * 360);
            $segment = [
                'label' => $sale->label,
                'files' => (int) $sale->files,
                'amount' => (float) $sale->amount,
                'percentage' => $percentage,
                'color' => $pieColors[$index % count($pieColors)],
                'start' => $pieStart,
                'end' => $end,
            ];
            $pieStart = $end;

            return $segment;
        });

        $pieGradient = $pieSegments->isNotEmpty()
            ? $pieSegments->map(fn ($segment) => "{$segment['color']} {$segment['start']}deg {$segment['end']}deg")->implode(', ')
            : '#e5e7eb 0deg 360deg';

        $dailyRevenueRows = Transaction::query()
            ->selectRaw('DATE(created_at) as day, SUM(amount) as amount')
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('amount', 'day');

        $dailyRevenue = collect(range(6, 0))->map(function ($daysAgo) use ($dailyRevenueRows) {
            $date = now()->subDays($daysAgo);
            $amount = (float) ($dailyRevenueRows[$date->toDateString()] ?? 0);

            return [
                'label' => $date->format('D'),
                'date' => $date->format('d M'),
                'amount' => $amount,
            ];
        });

        $dailyMax = max((float) $dailyRevenue->max('amount'), 1);

        $topEvents = TransactionItem::query()
            ->selectRaw('images.id, images.title, images.thumbnail_path, images.file_path, COUNT(transaction_items.id) as files, SUM(transaction_items.amount) as amount')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('images', 'images.id', '=', 'transaction_items.image_id')
            ->where('transactions.status', 'success')
            ->groupBy('images.id', 'images.title', 'images.thumbnail_path', 'images.file_path')
            ->orderByDesc('amount')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentImages', 'recentPayments', 'pieSegments', 'pieGradient', 'dailyRevenue', 'dailyMax', 'topEvents'));
    }
}
