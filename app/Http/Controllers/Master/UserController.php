<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(protected AuditLogService $auditLog) {}

    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->paginate(10);
        return view('master.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:owner,karyawan',
        ]);

        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = true;

        $user = User::create($data);
        $this->auditLog->catat('Master', 'create', "Menambahkan pengguna sistem baru: {$user->name} ({$user->role})");

        return redirect()->route('master.user.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id,
            'role'     => 'required|in:owner,karyawan',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $lama = $user->toArray();
        $user->update($data);
        $this->auditLog->catat('Master', 'update', "Memperbarui data pengguna: {$user->name}", $lama, $user->fresh()->toArray());

        return redirect()->route('master.user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password_baru' => 'required|string|min:6']);
        $user->update(['password' => Hash::make($request->password_baru)]);
        
        $this->auditLog->catat('Master', 'update', "Owner mereset password akun karyawan: {$user->name}");

        return redirect()->route('master.user.index')->with('success', "Password akun {$user->name} berhasil direset.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('master.user.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $lama = $user->toArray();
        $nama = $user->name;
        $user->delete();
        $this->auditLog->catat('Master', 'delete', "Menghapus akun pengguna: {$nama}", $lama, null);

        return redirect()->route('master.user.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
