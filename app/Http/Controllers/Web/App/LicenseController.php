<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Customer;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Display list of licenses
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $licenses = License::query()->with(['customer', 'rsaKey']);

            return DataTables::of($licenses)
                ->addIndexColumn()
                ->editColumn('license_id', function ($row) {
                    return '<div class="d-flex flex-column">
                                <span class="fw-bold">' . e($row->license_id) . '</span>
                                <span class="text-muted fs-7">' . e($row->product_key) . '</span>
                            </div>';
                })
                ->editColumn('customer_id', function ($row) {
                    return '<div class="d-flex flex-column">
                                <span class="fw-bold">' . e($row->customer->company_name) . '</span>
                                <span class="text-muted fs-7">' . e($row->customer->email) . '</span>
                            </div>';
                })
                ->editColumn('license_type', function ($row) {
                    return $row->license_type_badge;
                })
                ->editColumn('status', function ($row) {
                    return $row->status_badge;
                })
                ->editColumn('expiry_date', function ($row) {
                    $daysRemaining = $row->days_remaining;
                    $expiryText = $row->expiry_date->format('M d, Y');

                    if ($row->status === 'ACTIVE' && $daysRemaining <= 30) {
                        return '<div class="d-flex flex-column">
                                    <span>' . $expiryText . '</span>
                                    <span class="text-warning fs-7">' . $daysRemaining . ' days left</span>
                                </div>';
                    }

                    return $expiryText;
                })
                ->addColumn('action', function ($row) {
                    return view('layouts.partials.action-button.license.index', [
                        'row' => $row,
                    ])->render();
                })
                ->rawColumns(['license_id', 'customer_id', 'license_type', 'status', 'expiry_date', 'action'])
                ->make(true);
        }

        return view('pages.license.index');
    }

    /**
     * Show create license form
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)
            ->orderBy('company_name')
            ->get();

        return view('pages.license.create', compact('customers'));
    }

    /**
     * Store new license
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'license_type' => 'required|in:TRIAL,PERSONAL,PROFESSIONAL,ENTERPRISE,CUSTOM',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'max_devices' => 'required|integer|min:1|max:1000',
            'features' => 'required|json',
            'hardware_id' => 'nullable|string|max:100',
        ]);

        try {
            $license = $this->licenseService->createLicense($validated);

            return redirect()
                ->route('license.index')
                ->with('success', 'License generated successfully! License ID: ' . $license->license_id);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to generate license: ' . $e->getMessage());
        }
    }

    /**
     * Show license details
     */
    public function show(License $license)
    {
        $license->load(['customer', 'rsaKey']);

        return view('pages.license.show', compact('license'));
    }

    /**
     * Download PEM file
     */
    public function download(Request $request, $id)
    {
        try {
            $license = License::findOrFail($id);
            $filename = $license->license_id . '.pem';

            if (!Storage::exists('licenses/' . $filename)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'License file not found',
                    ], 404);
                }

                return redirect()
                    ->back()
                    ->with('error', 'License file not found');
            }

            return Storage::download('licenses/' . $filename, $filename);
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to download license: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to download license: ' . $e->getMessage());
        }
    }

    /**
     * Show edit/extend license form
     */
    public function edit($id)
    {
        $license = License::with(['customer', 'rsaKey'])->findOrFail($id);

        if (!$license->canBeExtended()) {
            return redirect()
                ->back()
                ->with('error', 'This license cannot be extended');
        }

        return view('pages.license.edit', compact('license'));
    }

    /**
     * Update/Extend license expiry
     */
    public function update(Request $request, $id)
    {
        $license = License::findOrFail($id);

        $validated = $request->validate([
            'expiry_date' => 'required|date|after:' . $license->issue_date,
        ]);

        try {
            $this->licenseService->extendLicense($license, $validated['expiry_date']);

            return redirect()
                ->route('license.index')
                ->with('success', 'License extended successfully! New PEM file generated.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to extend license: ' . $e->getMessage());
        }
    }

    /**
     * Revoke license
     */
    public function revoke(Request $request, $id)
    {
        try {
            $license = License::findOrFail($id);

            if (!$license->canBeRevoked()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This license cannot be revoked',
                    ], 400);
                }

                return redirect()
                    ->back()
                    ->with('error', 'This license cannot be revoked');
            }

            $validated = $request->validate([
                'revocation_reason' => 'nullable|string|max:500',
            ]);

            $this->licenseService->revokeLicense($license, $validated['revocation_reason'] ?? null);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'License revoked successfully!',
                ]);
            }

            return redirect()
                ->route('license.index')
                ->with('success', 'License revoked successfully!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to revoke license: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to revoke license: ' . $e->getMessage());
        }
    }

    /**
     * Delete license
     */
    public function destroy(Request $request, $id)
    {
        try {
            $license = License::findOrFail($id);

            if (!$license->canBeDeleted()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Active licenses cannot be deleted. Revoke it first.',
                    ], 400);
                }

                return redirect()
                    ->back()
                    ->with('error', 'Active licenses cannot be deleted. Revoke it first.');
            }

            // Delete PEM file from storage
            $filename = 'licenses/' . $license->license_id . '.pem';
            if (Storage::exists($filename)) {
                Storage::delete($filename);
            }

            $license->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'License deleted successfully!',
                ]);
            }

            return redirect()
                ->route('license.index')
                ->with('success', 'License deleted successfully!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete license: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete license: ' . $e->getMessage());
        }
    }
}
