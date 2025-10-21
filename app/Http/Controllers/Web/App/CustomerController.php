<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query();

            return DataTables::of($customers)
                ->addIndexColumn() // Adds DT_RowIndex
                ->editColumn('company_name', function ($row) {
                    return '<div class="d-flex flex-column">
                                <span class="fw-bold">' . e($row->company_name) . '</span>
                                <span class="text-muted fs-7">' . e($row->contact_name) . '</span>
                            </div>';
                })
                ->editColumn('email', function ($row) {
                    return '<a href="mailto:' . e($row->email) . '" class="text-gray-800 text-hover-primary">' . e($row->email) . '</a>';
                })
                ->editColumn('phone', function ($row) {
                    return $row->phone ?? '<span class="text-muted">-</span>';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? 1 : 0;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('M d, Y');
                })
                ->addColumn('licenses_count', function ($row) {
                    return $row->active_license_count;
                })
                ->addColumn('action', function ($row) {
                    return view('layouts.partials.action-button.customer.index', [
                        'row' => $row,
                    ])->render();
                })
                ->rawColumns(['company_name', 'email', 'action'])
                ->make(true);
        }

        return view('pages.customer.index');
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('pages.customer.create');
    }

    /**
     * Store a newly created customer in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $customer = Customer::create($validated);

            return redirect()
                ->route('customer.index')
                ->with('success', 'Customer created successfully!');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        $customer->load('licenses');

        return view('pages.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('pages.customer.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $customer->update($validated);

            return redirect()
                ->route('customer.index')
                ->with('success', 'Customer updated successfully!');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    /**
     * Activate/Deactivate customer
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $newStatus = !$customer->is_active;
            $customer->update(['is_active' => $newStatus]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $newStatus ? 'Customer activated successfully!' : 'Customer deactivated successfully!',
                ]);
            }

            return redirect()
                ->route('customer.index')
                ->with('success', $newStatus ? 'Customer activated successfully!' : 'Customer deactivated successfully!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update customer status: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to update customer status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Check if customer has active licenses
            if ($customer->hasActiveLicenses()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete customer with active licenses. Please deactivate or remove licenses first.',
                    ], 400);
                }

                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete customer with active licenses. Please deactivate or remove licenses first.');
            }

            $customer->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer deleted successfully!',
                ]);
            }

            return redirect()
                ->route('customer.index')
                ->with('success', 'Customer deleted successfully!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete customer: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete customers
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:customers,id',
        ]);

        try {
            $customersWithLicenses = Customer::whereIn('id', $request->ids)
                ->whereHas('licenses', function ($query) {
                    $query->where('status', 'ACTIVE');
                })
                ->count();

            if ($customersWithLicenses > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some customers have active licenses and cannot be deleted.',
                ], 400);
            }

            Customer::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' customers deleted successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customers: ' . $e->getMessage(),
            ], 400);
        }
    }
}
