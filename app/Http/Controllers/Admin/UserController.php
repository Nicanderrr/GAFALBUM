<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'service_number' => ['required', 'string', 'max:255', 'unique:users'],
        ]);

        $password = Str::password(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'service_number' => $request->service_number,
            'password' => Hash::make($password),
        ]);

        Mail::to($user->email)->send(new UserCreatedMail($user, $password));

        return redirect()->route('admin.users.index')->with('success', 'User created successfully and email sent.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'service_number' => ['required', 'string', 'max:255', 'unique:users,service_number,'.$user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'service_number' => $request->service_number,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
