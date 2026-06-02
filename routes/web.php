<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'customers_count' => \App\Models\Customer::count(),
            'proposals_count' => \App\Models\Proposal::count(),
            'invoices_count' => \App\Models\Invoice::count(),
            'invoice_status_counts' => \App\Models\Invoice::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status'),
            'transactions_count' => \App\Models\Transaction::count(),
            'transactions_sum_amount' => \App\Models\Transaction::sum('amount') ?? 0,
            'transactions' => \App\Models\Transaction::with('invoice.customer')
                ->latest()
                ->take(3)
                ->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'amount' => '$' . number_format($t->amount, 2),
                    'date' => $t->created_at->format('M d, Y'),
                    'customer' => ['name' => $t->invoice->customer->name ?? 'Unknown'],
                ]),
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::patch('/customers/{customer}/status', [CustomerController::class, 'toggleStatus'])
        ->name('customers.toggleStatus');

    Route::resource('proposals', ProposalController::class)->except(['show']);
    Route::patch('/proposals/{proposal}/status', [ProposalController::class, 'changeStatus'])
        ->name('proposals.changeStatus');

    Route::resource('invoices', InvoiceController::class)->except(['show']);
    Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'changeStatus'])
        ->name('invoices.changeStatus');
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])
        ->name('invoices.send');
    Route::get('/invoices/payment-success', [InvoiceController::class, 'paymentSuccess'])
        ->name('invoices.paymentSuccess');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
});

// Stripe webhook — outside auth middleware, excluded from CSRF
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');

require __DIR__.'/auth.php';

