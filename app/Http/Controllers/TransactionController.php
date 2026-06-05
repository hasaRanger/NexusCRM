<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class TransactionController extends Controller
{
    public function index(Request $request): Response
    {
        $transactions = Transaction::query();
        
        if($search = $request->input('search')) {
            $transactions->where(function ($q) use ($search) {
                $q->where('amount', 'like', "%$search%")
                  ->orWhere('gateway', 'like', "%$search%")
                  ->orWhere('reference', 'like', "%$search%")
                  ->orWhere('paid_at', 'like', "%$search%")
                  ->orWhere('created_at', 'like', "%$search%")
                  ->orWhereHas('invoice', function ($i) use ($search) {
                      $i->where('invoice_number', 'like', "%$search%");
                  })
                  ->orWhereHas('invoice.customer', function ($c) use ($search) {
                      $c->where('name', 'like', "%$search%");
                  });
            });
        }   
        
        if($gateway = $request->input('gateway')) {
            if(in_array($gateway, ['stripe', 'manual'])) {
                $transactions->where('gateway', $gateway);
            }
        } 

        $allowedSortColumns = ['amount', 'gateway', 'reference', 'paid_at', 'created_at'];
        $sortBy = $request->input('sort_by') ?? 'created_at';
        $sortDirection = $request->input('sort_direction') ?? 'desc';

        if (! in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        if (! in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $transactions->orderBy($sortBy, $sortDirection);
        
        $transactions = $transactions->with('invoice.customer')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'filters' => [
                'search' => $request->input('search', ''),
                'gateway' => $request->input('gateway', 'all'),
            ],
            'sort' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }
}
