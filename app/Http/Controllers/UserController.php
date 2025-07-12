<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['Admin', 'Lurah'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sign' => 'nullable|image|mimes:png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        if ($request->hasFile('sign')) {
            $data['sign'] = $request->file('sign')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['Admin', 'Lurah'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sign' => 'nullable|image|mimes:png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        if ($request->hasFile('sign')) {
            // Hapus tanda tangan lama jika ada
            if ($user->sign) {
                Storage::disk('public')->delete($user->sign);
            }
            $data['sign'] = $request->file('sign')->store('users', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Cegah penghapusan user yang sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete current logged-in user.');
        }

        // Hapus foto dan tanda tangan jika ada
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }
        if ($user->sign) {
            Storage::disk('public')->delete($user->sign);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Toggle status aktif/tidak aktif (misalnya, menambahkan kolom is_active di model User)
        $user->update(['is_active' => !$user->is_active]);
        return redirect()->route('users.index')->with('success', 'User status updated successfully.');
    }
}