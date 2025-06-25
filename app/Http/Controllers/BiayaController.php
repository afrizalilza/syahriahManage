<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    public function index()
    {
        $biayas = \App\Models\Biaya::all();

        return view('biaya.index', compact('biayas'));
    }

    public function create()
    {
        return view('biaya.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_biaya' => 'required|string|max:255|unique:biayas,nama_biaya,NULL,id,unit,'.$request->input('unit'),
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ], [
            'nama_biaya.unique' => 'Nama biaya sudah terdaftar. Tidak boleh duplikat.',
        ]);

        // Otomatisasi pengisian unit
        $user = auth()->user();
        if ($user->role === 'admin') {
            $validated['unit'] = $request->input('unit'); // dari form admin
        } elseif ($user->role === 'bendahara') {
            $validated['unit'] = $user->unit; // otomatis dari user
        }
        Biaya::create($validated);

        return redirect()->route('biaya.index')->with('success', 'Data biaya berhasil ditambahkan.');
    }

    public function show($id)
    {
        $biaya = Biaya::findOrFail($id);

        return view('biaya.show', compact('biaya'));
    }

    public function edit($id)
    {
        $biaya = Biaya::findOrFail($id);

        return view('biaya.edit', compact('biaya'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $rules = [
            'nama_biaya' => 'required|string|max:255|unique:biayas,nama_biaya,'.$id.',id,unit,'.$request->input('unit'),
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ];
        if ($user->role === 'admin') {
            $rules['unit'] = 'required|in:putra,putri';
        }
        $validated = $request->validate($rules, [
            'nama_biaya.unique' => 'Nama biaya sudah terdaftar. Tidak boleh duplikat.',
            'unit.required' => 'Unit wajib dipilih.',
            'unit.in' => 'Unit harus putra atau putri.',
        ]);
        $biaya = Biaya::findOrFail($id);
        // Otomatisasi pengisian unit
        $user = auth()->user();
        if ($user->role === 'admin') {
            $validated['unit'] = $request->input('unit'); // dari form admin
        } elseif ($user->role === 'bendahara') {
            $validated['unit'] = $user->unit; // otomatis dari user
        }
        $biaya->update($validated);

        return redirect()->route('biaya.index')->with('success', 'Data biaya berhasil diupdate.');
    }

    public function destroy($id)
    {
        $biaya = Biaya::findOrFail($id);
        $biaya->delete();

        return redirect()->route('biaya.index')->with('success', 'Data biaya berhasil dihapus.');
    }
}
