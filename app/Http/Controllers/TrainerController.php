<?php
namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class TrainerController extends Controller
{
    /**
     * Display a listing of the trainers.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Trainer::query()->select('*'); // Ensure all fields are fetched

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('photo', function($trainer) {
                    if ($trainer->photo && Storage::disk('public')->exists($trainer->photo)) {
                        $url = asset('public/storage/' . $trainer->photo);
                        return '<img src="' . $url . '" alt="Trainer Photo" width="45" height="45" style="object-fit: cover;" class="img-thumbnail shadow-sm">';
                    }
                    return '<div class="d-flex align-items-center justify-content-center bg-light rounded-circle border" style="width: 45px; height: 45px;">
                            <i class="fa fa-user text-secondary"></i>
                        </div>';
                })
                // Ensure gender is capitalized for display
                ->editColumn('gender', function($trainer) {
                    return ucfirst($trainer->gender);
                })
                ->addColumn('status', function($trainer) {
                    return $trainer->status == 'active'
                        ? '<span class="badge rounded-pill bg-success">Active</span>'
                        : '<span class="badge rounded-pill bg-danger">Inactive</span>';
                })
                ->editColumn('specialization', function($trainer) {
                    return $trainer->specialization ? Str::limit($trainer->specialization, 30) : 'N/A';
                })
                ->addColumn('action', function($trainer) {
                    $edit = '<a href="' . route('trainers.edit', $trainer->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $delete = '<form action="' . route('trainers.destroy', $trainer->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() .  method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>
                          </form>';

                    return '<div class="action d-flex gap-2">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['photo', 'status', 'action'])
                ->make(true);
        }

        return view('trainers.manage');
    }

    /**
     * Show the form for creating a new trainer.
     */
    public function create()
    {
        return view('trainers.add');
    }

    /**
     * Store a newly created trainer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'phone_number' => 'nullable|string|max:20',
            'trainer_type' => 'nullable|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'in:active,inactive',
            'specialization' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('trainers', 'public');
            $validated['photo'] = $path;
        }

        Trainer::create($validated);

        return redirect()->route('trainers.add')->with('success', 'Trainer created successfully.');
    }

    /**
     * Show the form for editing the specified trainer.
     */
    public function edit(Trainer $trainer)
    {
        return view('trainers.edit', compact('trainer'));
    }

    /**
     * Update the specified trainer in storage.
     */
    public function update(Request $request, Trainer $trainer)
    {
    }

    /**
     * Remove the specified trainer from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        return redirect()->route('trainers.manage')->with('success', 'Trainer deleted successfully.');
    }
}
