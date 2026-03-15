<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $user  = Auth::user();
        $now   = Carbon::now();
        $today = Carbon::today();

        // Scenario A: User clicks "CHECK IN NOW"
        if ($request->action === 'checkin') {
            Attendance::create([
                'user_id'         => $user->id,
                'checkin_time'    => $now,
                'attendance_date' => $today,
            ]);

            return back()->with('success', __('Check-in successful! Welcome to Sky Fitness.'));
        }

        // Scenario B: User clicks "CHECK OUT NOW"
        if ($request->action === 'checkout') {
            // Find the record for today where checkout_time is still NULL
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('attendance_date', $today)
                ->whereNull('checkout_time')
                ->first();

            if ($attendance) {
                $attendance->update([
                    'checkout_time' => $now,
                ]);
                return back()->with('success', __('Check-out successful! Have a great day.'));
            }

            return back()->with('error', __('Active session not found.'));
        }
    }
}
