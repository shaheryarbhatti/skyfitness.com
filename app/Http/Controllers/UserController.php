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
                ->whereDoesntHave('roles', function($q) {
                    $q->where('name', 'Super Admin');
                })
                ->select('users.*');

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    return $user->roles->first()->name ?? 'No Role';
                })
                ->addColumn('action', function ($user) {
                    $edit = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $delete = '<form action="' . route('users.destroy', $user->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i></button>
                          </form>';

                    return '<div class="action d-flex gap-2">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['action'])
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if ($request->role_id) {
            $role = Role::find($request->role_id);
            $user->assignRole($role);
        }

        return redirect()->route('users.add')
            ->with('success', 'User created successfully.');
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Update role
        if ($request->role_id) {
            $role = Role::find($request->role_id);
            $user->syncRoles([$role->name]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.manage')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.manage')
            ->with('success', 'User deleted successfully.');
    }
}
