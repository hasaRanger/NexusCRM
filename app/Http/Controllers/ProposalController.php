<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProposalController extends Controller
{
    public function index(): Response
    {
        $proposals = Proposal::with('customer:id,name')
            ->latest()
            ->paginate(15);

        return Inertia::render('Proposals/Index', [
            'proposals' => $proposals,
        ]);
    }

    public function create(): Response
    {
        $customers = Customer::where('status', 'active')
            ->select(['id', 'name'])
            ->get();

        return Inertia::render('Proposals/Create', [
            'customers' => $customers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'status'      => ['required', 'in:draft,sent,accepted,rejected'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        Proposal::create($validated);

        return redirect()->route('proposals.index')
            ->with('success', 'Proposal created successfully.');
    }

    public function edit(Proposal $proposal): Response
    {
        $proposal->load('customer:id,name');
        
        $customers = Customer::where('status', 'active')
            ->select(['id', 'name'])
            ->get();

        return Inertia::render('Proposals/Edit', [
            'proposal'  => $proposal,
            'customers' => $customers,
        ]);
    }

    public function update(Request $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'status'      => ['required', 'in:draft,sent,accepted,rejected'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $proposal->update($validated);

        return redirect()->route('proposals.index')
            ->with('success', 'Proposal updated successfully.');
    }

    public function destroy(Proposal $proposal): RedirectResponse
    {
        $proposal->delete();

        return redirect()->route('proposals.index')
            ->with('success', 'Proposal deleted.');
    }

    public function changeStatus(Request $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,sent,accepted,rejected'],
        ]);

        $proposal->update($validated);

        return back()->with('success', 'Status updated.');
    }
}
