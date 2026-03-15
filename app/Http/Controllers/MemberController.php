<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index()
    {
        if (request()->ajax()) {
            // Use eager loading if Member has relationships (e.g., Member::with('group'))
            $query = Member::query();

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('photo', function ($member) {
                    // Check if file exists in the 'public' disk
                    if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                        $url = asset('public/storage/' . $member->photo);
                        return '<img src="' . $url . '" alt="Member Photo" width="50" height="150" style="object-fit: cover;" class="img-thumbnail  shadow-sm">';
                    }

                    // Fallback to a placeholder if no photo exists
                    return '<div class="d-flex align-items-center justify-content-center bg-light rounded-circle border" style="width: 50px; height: 50px;">
                            <i class="fa fa-user text-secondary"></i>
                        </div>';
                })
                ->addColumn('status', function ($member) {
                    // Using Bootstrap 5 badges
                    return $member->status
                        ? '<span class="badge rounded-pill bg-success">Active</span>'
                        : '<span class="badge rounded-pill bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($member) {
                    $edit = '<a href="' . route('members.edit', $member->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    // $view   = '<a href="' . route('members.show', $member->id) . '" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                    $delete = '<form action="' . route('members.destroy', $member->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>
                          </form>';

                    return '<div class="action d-flex gap-2">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['photo', 'status', 'action'])
                ->make(true);
        }

        return view('members.manage');
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('members.add');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:100',
            'nik'            => 'nullable|string|unique:members,nik',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'nullable|in:male,female,other',
            'blood_type'     => 'nullable|string|max:5',
            'religion'       => 'nullable|string|max:50',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'occupation'     => 'nullable|string|max:100',
            'citizenship'    => 'nullable|string|max:100',
            'email'          => 'nullable|email|unique:members,email|max:120',
            'phone'          => 'nullable|string|max:20|unique:members,phone',
            'address'        => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'status'         => 'boolean',
            'password'       => 'nullable|string|min:6',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path               = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        // Set default status if not provided
        $validated['status'] = $request->has('status') ? 1 : 0;

        $member = Member::create($validated);

        // Create or update corresponding user account
        if (! empty($validated['email'])) {
            $password = $request->input('password');
            if (empty($password)) {
                $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*'), 0, 6);
            }

            $user = User::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'name'     => $validated['full_name'],
                    'password' => Hash::make($password),
                    'image'    => $path, // <--- ADDED THIS LINE
                ]
            );

            $memberRole = Role::where('name', 'Member')->first();
            if ($memberRole && ! $user->hasRole($memberRole->name)) {
                $user->assignRole($memberRole);
            }
        }

        return redirect()->route('members.add')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:100',
            'nik'            => 'nullable|string|unique:members,nik,' . $member->id,
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'nullable|in:male,female,other',
            'blood_type'     => 'nullable|string|max:5',
            'religion'       => 'nullable|string|max:50',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'occupation'     => 'nullable|string|max:100',
            'citizenship'    => 'nullable|string|max:100',
            'email'          => 'nullable|email|unique:members,email,' . $member->id . '|max:120',
            'phone'          => 'nullable|string|max:20|unique:members,phone,' . $member->id,
            'address'        => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'         => 'boolean',
            'password'       => 'nullable|string|min:6',
        ]);

        // Handle photo update / replacement
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $path               = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        $validated['status'] = $request->has('status') ? 1 : 0;

        $oldEmail = $member->email;
        $member->update($validated);

        // Keep user record in sync
        if (! empty($validated['email'])) {
            $user = User::where('email', $oldEmail)
                ->orWhere('email', $validated['email'])
                ->first();

            if (! $user) {
                $user = new User();
            }

            $user->name  = $validated['full_name'];
            $user->email = $validated['email'];

            // SYNC IMAGE TO USER TABLE
            // If a new photo was uploaded, update the user image column
            if ($request->hasFile('photo')) {
                $user->image = $validated['photo'];
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            $memberRole = Role::where('name', 'Member')->first();
            if ($memberRole && ! $user->hasRole($memberRole->name)) {
                $user->assignRole($memberRole);
            }
        }

        return redirect()->route('members.manage')
            ->with('success', __('Member and User profile updated successfully.'));
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Member $member)
    {
        // 1. Delete photo from storage if it exists
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        // 2. Find and delete the User based on the email link
        // We do this before deleting the member to ensure we have the email reference
        User::where('email', $member->email)->delete();

        // 3. Delete the Member record
        $member->delete();

        return redirect()->route('members.manage')
            ->with('success', __('Member and associated user account deleted successfully.'));
    }
}
