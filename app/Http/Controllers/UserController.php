<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = User::with('roles')
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'Super Admin');
                })
                ->select('users.*');

            return datatables()->of($query)
                ->addIndexColumn()
            // Transform the name column to include the avatar
                ->editColumn('name', function ($user) {
                    $url = $user->image
                        ? asset('public/storage/' . $user->image)
                        : asset('public/assets/images/dashboard/profile.png');

                    return '<div class="d-flex align-items-center">
                            <img src="' . $url . '" class="rounded-circle me-2"
                                 style="width: 35px; height: 35px; object-fit: cover; border: 1px solid #eee;">
                            <span>' . $user->name . '</span>
                        </div>';
                })
                ->addColumn('role', function ($user) {
                    return $user->roles->first()->name ?? 'No Role';
                })
            // Add the status badge column
                ->addColumn('status', function ($user) {
                    $statusClass = $user->status ? 'badge-light-success' : 'badge-light-danger';
                    $statusText  = $user->status ? __('active') : __('inactive');

                    return '<span class="badge rounded-pill ' . $statusClass . '">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($user) {
                    $edit   = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $delete = '<form action="' . route('users.destroy', $user->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>
                          </form>';

                    return '<div class="action d-flex gap-2">' . $edit . $delete . '</div>';
                })
            // Ensure 'status' is added to rawColumns
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('users.manage');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('users.add', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'nullable|exists:roles,id',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $path               = $request->file('image')->store('members', 'public');
            $validated['image'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);
        $user                  = User::create($validated);

        if ($request->role_id) {
            $user->assignRole(Role::find($request->role_id));
        }

        return redirect()->route('users.add')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id'  => 'nullable|exists:roles,id',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        // Handle Image Update
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($user->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->image);
            }

            if (! \Illuminate\Support\Facades\Storage::disk('public')->exists('members')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('members');
            }

            $path               = $request->file('image')->store('members', 'public');
            $validated['image'] = $path;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->role_id) {
            $user->syncRoles([Role::find($request->role_id)->name]);
        }

        return redirect()->route('users.manage')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->image);
        }
        $user->delete();

        return redirect()->route('users.manage')
            ->with('success', 'User deleted successfully.');
    }
}
