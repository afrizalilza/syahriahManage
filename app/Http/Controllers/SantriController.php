<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $santris = Santri::query();
        if ($q) {
            $santris->where(function ($query) use ($q) {
                $query->where('nama', 'like', "%$q%")
                      ->orWhere('nis', 'like', "%$q%")
                      ->orWhere('alamat', 'like', "%$q%")
                      ->orWhere('jenis_kelamin', 'like', "%$q%")
                      ->orWhere('status', 'like', "%$q%");
            });
        }
        $santris = $santris->get();

        return view('santri.index', compact('santris')); //compact = array //santri.index (santri itu folder) (index adalah file index yang ada di folder santri)
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:santris,nis',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:Aktif,Nonaktif',
        ];
        if (auth()->user()->role === 'admin') {
            $rules['unit'] = 'required|in:putra,putri';
        }
        $validated = $request->validate($rules, [
            'nis.unique' => 'NIS sudah terdaftar. Tidak boleh duplikat.',
            'unit.required' => 'Unit wajib dipilih.',
            'unit.in' => 'Unit harus putra atau putri.',
        ]);
        $sama = \App\Models\Santri::where('nama', $validated['nama'])
            ->where('tanggal_masuk', $validated['tanggal_masuk'])
            ->exists();
        if ($sama) {
            return back()->withErrors('Santri dengan nama dan tanggal masuk yang sama sudah ada.')->withInput();
        }
        // Otomatisasi pengisian unit
        $user = auth()->user();
        if ($user->role === 'admin') {
            $validated['unit'] = $request->input('unit'); // dari form admin
        } elseif ($user->role === 'bendahara') {
            $validated['unit'] = $user->unit; // otomatis dari user
        }
        Santri::create($validated);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function show($id)
    {
        $santri = Santri::findOrFail($id);

        return view('santri.show', compact('santri'));
    }

    public function edit($id)
    {
        $santri = Santri::findOrFail($id);

        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:santris,nis,'.$id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:Aktif,Nonaktif',
        ];
        if (auth()->user()->role === 'admin') {
            $rules['unit'] = 'required|in:putra,putri';
        }
        $validated = $request->validate($rules, [
            'nis.unique' => 'NIS sudah terdaftar. Tidak boleh duplikat.',
            'unit.required' => 'Unit wajib dipilih.',
            'unit.in' => 'Unit harus putra atau putri.',
        ]);
        $sama = \App\Models\Santri::where('nama', $validated['nama'])
            ->where('tanggal_masuk', $validated['tanggal_masuk'])
            ->where('id', '!=', $id)
            ->exists();
        if ($sama) {
            return back()->withErrors('Santri dengan nama dan tanggal masuk yang sama sudah ada.')->withInput();
        }
        $santri = Santri::findOrFail($id);
        // Otomatisasi pengisian unit
        $user = auth()->user();
        if ($user->role === 'admin') {
            $validated['unit'] = $request->input('unit'); // dari form admin
        } elseif ($user->role === 'bendahara') {
            $validated['unit'] = $user->unit; // otomatis dari user
        }
        $santri->update($validated);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diupdate.');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        $santri->delete();

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }
}
