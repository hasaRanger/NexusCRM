<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    public function index(): Response
    {
        $transactions = Transaction::with('invoice.customer')
            ->latest()
            ->paginate(15);

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }
}
