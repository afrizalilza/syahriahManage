<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Tampilkan daftar user dengan role 'pending'
    public function pending()
    {
        $pendingUsers = User::where('role', 'pending')->get();

        return view('user.pending', compact('pendingUsers'));
    }

    // Approve user (ubah role)
    public function approve(Request $request, $id)
    {
        $rules = [
            'role' => 'required|in:admin,bendahara,wali_santri,santri',
        ];
        if ($request->role === 'bendahara') {
            $rules['unit'] = 'required|in:putra,putri';
        }
        $validated = $request->validate($rules);
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->unit = $request->role === 'bendahara' ? $request->unit : null;
        $user->save();

        return redirect()->route('user.pending')->with('success', 'User berhasil di-approve dengan role: '.$request->role.($user->unit ? ' unit: '.$user->unit : ''));
    }
}
