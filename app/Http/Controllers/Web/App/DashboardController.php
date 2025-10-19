<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\License;
use App\Models\RsaKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::active()->count();
        $totalLicenses = License::count();
        $activeLicenses = License::active()->count();
        $expiredLicenses = License::expired()->count();
        $revokedLicenses = License::revoked()->count();
        $expiringSoon = License::expiringSoon(30)->count();
        $totalRsaKeys = RsaKey::count();
        $activeRsaKey = RsaKey::active()->first();
        $totalUsers = User::count();

        // Get recent licenses (last 10)
        $recentLicenses = License::with(['customer', 'rsaKey'])
            ->latest()
            ->limit(10)
            ->get();

        // Get license statistics by type
        $licensesByType = License::select('license_type', DB::raw('count(*) as total'))
            ->groupBy('license_type')
            ->get()
            ->pluck('total', 'license_type');

        // Get license statistics by status
        $licensesByStatus = License::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Get licenses expiring in the next 7, 14, 30 days
        $expiringIn7Days = License::expiringSoon(7)->count();
        $expiringIn14Days = License::expiringSoon(14)->count();
        $expiringIn30Days = License::expiringSoon(30)->count();

        // Get monthly license creation trend (last 6 months)
        $monthlyLicenses = License::where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('pages.dashboard.index', compact(
            'totalCustomers',
            'activeCustomers',
            'totalLicenses',
            'activeLicenses',
            'expiredLicenses',
            'revokedLicenses',
            'expiringSoon',
            'totalRsaKeys',
            'activeRsaKey',
            'totalUsers',
            'recentLicenses',
            'licensesByType',
            'licensesByStatus',
            'expiringIn7Days',
            'expiringIn14Days',
            'expiringIn30Days',
            'monthlyLicenses'
        ));
    }
}
