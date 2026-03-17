<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Fetch data with Member relationship
            $query = Attendance::with('member')->select('attendances.*');

            // Apply Date Filter using created_at
            if ($request->has('date') && ! empty($request->date)) {
                $query->whereDate('created_at', $request->date);
            }

            $data = $query->get()->toArray();

            return DataTables::of($query)
                ->addIndexColumn()

            // Member Column with Image
                ->addColumn('member_name', function ($row) {
                    $imagePath = $row->member->image
                        ? asset('public/storage/' . $row->member->image)
                        : asset('public/assets/images/dashboard/user.png');

                    return '
                        <div class="d-flex align-items-center">
                            <img src="' . $imagePath . '" class="img-30 rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-0 f-14">' . $row->member->full_name . '</h6>
                            </div>
                        </div>';
                })

            // Status Badge (Standardized to lowercase for JSON keys)


            // Extracting Date from created_at
                ->editColumn('attendance_date', function ($row) {
                    return $row->created_at->format('d M, Y');
                })

            // Check-in / Check-out Formatting
                ->editColumn('check_in', function ($row) {
                    return $row->checkin_time ? Carbon::parse($row->checkin_time)->format('h:i A') : '-';
                })
                ->editColumn('check_out', function ($row) {
                    return $row->checkout_time ? Carbon::parse($row->checkout_time)->format('h:i A') : '-';
                })

                ->rawColumns(['member_name'])
                ->make(true);
        }

        return view('attendance.manage');
    }

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
