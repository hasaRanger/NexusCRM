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
    public function index(Request $request): Response
    {        
        $proposals = Proposal::query();

        // Apply customer name filtering if search term is provided
        if ($search = $request->input('search')) {
            $proposals->whereHas('customer', function ($p) use ($search) {
                $p->where('title', 'like', '%' . $search . '%')
                   ->orWhere('description', 'like', '%' . $search . '%')
                   ->orWhere('amount', 'like', '%' . $search . '%')
                   ->orWhere('valid_until', 'like', '%' . $search . '%')
                   ->orWhere('created_at', 'like', '%' . $search . '%');
            });
        }

        // Apply status filtering
        if ($status = $request->input('status')) {
            if(in_array($status, ['draft', 'sent', 'accepted', 'rejected'])){
                $proposals->where('status', $status);
            }
        }

        // Apply sorting
        $allowedSortColumns = ['title', 'description', 'amount', 'valid_until', 'created_at'];
        $sortBy = $request->input('sort_by') ?? 'created_at';
        $sortDirection = $request->input('sort_direction') ?? 'desc';

        if (! in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        if (! in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $proposals->orderBy($sortBy, $sortDirection);

        $proposals = $proposals->with('customer:id,name')->paginate(15)->withQueryString();

        return Inertia::render('Proposals/Index', [
            'proposals' => $proposals,
            'filters'   => [
                'search' => $request->input('search', ''),
                'status' => $request->input('status', 'all'),
            ],
            'sort'      => [
                'sort_by'        => $sortBy,
                'sort_direction' => $sortDirection,
            ],
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
