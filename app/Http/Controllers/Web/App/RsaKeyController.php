<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\RsaKey;
use App\Models\User;
use App\Services\RsaKeyService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RsaKeyController extends Controller
{
    protected $rsaKeyService;

    public function __construct(RsaKeyService $rsaKeyService)
    {
        $this->rsaKeyService = $rsaKeyService;
    }

    /**
     * Display list of RSA keys
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rsaKeys = RsaKey::query()->orderBy('generated_at', 'desc');

            return DataTables::of($rsaKeys)
                ->addIndexColumn() // Adds DT_RowIndex
                ->editColumn('name', function ($row) {
                    return $row->display_name;
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? 1 : 0;
                })
                ->editColumn('generated_at', function ($row) {
                    return $row->generated_at->format('M d, Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return view('layouts.partials.action-button.rsakey.index', [
                        'row' => $row,
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.rsakey.index');
    }

    /**
     * Generate new RSA key pair
     */
    public function generate(Request $request)
    {
        try {
            $rsaKey = $this->rsaKeyService->generateKeyPair();

            return redirect()
                ->route('rsakey.index')
                ->with('success', 'RSA key pair generated successfully!');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to generate RSA key: ' . $e->getMessage());
        }
    }


    /**
     * Set RSA key as active
     */
    public function activate(Request $request, RsaKey $rsaKey)
    {
        try {
            $this->rsaKeyService->setActive($rsaKey);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'RSA key activated successfully!',
                ]);
            }

            return redirect()
                ->route('rsakey.index')
                ->with('success', 'RSA key activated successfully!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to activate key: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to activate key: ' . $e->getMessage());
        }
    }

    /**
     * Get public key (for modal/download)
     */
    public function publicKey(RsaKey $rsaKey)
    {
        return response()->json([
            'success' => true,
            'public_key' => $rsaKey->public_key,
            'name' => $rsaKey->name,
        ]);
    }

    /**
     * Download public key as .pub file
     */
    public function downloadPublicKey(RsaKey $rsaKey)
    {
        $filename = str_replace(' ', '_', $rsaKey->name) . '.pub';

        return response($rsaKey->public_key)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Delete RSA key
     */
    public function destroy(Request $request, RsaKey $rsaKey)
    {
        try {
            if (!$this->rsaKeyService->canDelete($rsaKey)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete this key. It may have licenses or is the only key.',
                    ], 400);
                }

                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete this key. It may have licenses or is the only key.');
            }

            $rsaKey->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'RSA key deleted successfully!',
                ]);
            }

            return redirect()
                ->route('rsakey.index')
                ->with('success', 'RSA key deleted successfully!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete key: ' . $e->getMessage(),
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete key: ' . $e->getMessage());
        }
    }
}
