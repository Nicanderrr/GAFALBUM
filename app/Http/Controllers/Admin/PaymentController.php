<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->string('search')),
            'status' => $request->string('status')->toString(),
        ];

        $paymentsQuery = Transaction::query()
            ->with(['user', 'image', 'items.media'])
            ->withCount('items');

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $paymentsQuery->where(function ($query) use ($search) {
                $query
                    ->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('service_number', 'like', "%{$search}%"))
                    ->orWhereHas('image', fn ($imageQuery) => $imageQuery->where('title', 'like', "%{$search}%"));
            });
        }

        if (in_array($filters['status'], ['pending', 'success', 'failed'], true)) {
            $paymentsQuery->where('status', $filters['status']);
        }

        $payments = $paymentsQuery->latest()->paginate(12)->withQueryString();

        $summary = [
            'all' => Transaction::count(),
            'success' => Transaction::where('status', 'success')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'revenue' => (float) Transaction::where('status', 'success')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'filters', 'summary'));
    }
}
