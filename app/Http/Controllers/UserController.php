<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'department'  => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'role'        => 'required|in:admin,member',
            'password'    => 'required|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Team member account created.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'department'  => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'role'        => 'required|in:admin,member',
            // password is optional on edit — only updated if provided
            'password'    => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User details updated.');
    }
}
