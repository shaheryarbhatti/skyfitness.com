<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 1. Update current user's login date/time
        $user                = Auth::user();
        $user->last_login_at = now();
        $user->save();

        // 2. Fetch Stats
        $totalMembers  = Member::count();
        $activeMembers = Member::where('status', 1)->count(); // 1 for active
        $totalTrainers = Trainer::count();

        // 3. Fetch Recent Activity (Last 5 users who logged in)
        // We exclude sensitive roles if necessary, or show all
        $recentActivity = User::whereNotNull('last_login_at')->withoutRole('Super Admin')
            ->orderBy('last_login_at', 'desc')
            ->take(5)
            ->get();

                                                  // 4. Fetch Recent Invoices
        $recentInvoices = Invoice::with('member') // Assuming Invoice has a member relationship
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $isCheckedIn = false;
        if ($user->hasRole('Member')) {
            // Check if there is an attendance record for today that doesn't have a checkout time yet
            $isCheckedIn = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->whereNull('checkout_time') // Assuming 'checkout_time' is your column name
                ->exists();
        }

        return view('dashboard/dashboard', compact(
            'totalMembers',
            'activeMembers',
            'totalTrainers',
            'recentActivity',
            'recentInvoices',
            'isCheckedIn'
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'You have been logged out successfully.');
    }
}
