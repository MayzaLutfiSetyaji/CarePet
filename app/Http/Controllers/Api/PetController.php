<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    //function: menampilkan daftar hewan milik user yang sedang login
    public function index(Request $request) {
        $pets = Pet::where('user_id', $request->user()->id)->get();

        return response()->json([
            'message' => 'Berhasil menampilkan daftar hewan',
            'data' => $pets
        ]);
    }

    //function: menambahkan data hewan baru
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255', //contoh: kucing/anjing
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'special_notes' => 'nullable|string', //catatan untuk hewan peliharaan
        ]);

        $pet = Pet::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'age' => $request->age,
            'special_notes' => $request->special_notes,
        ]);

        return response().json([
            'message' => 'Hewan peliharaan berhasil ditambahkan',
            'data' => $pet
        ], 201);
    }

    //function: menampilkan detail hewan tertentu (GET /api/v1/pet/{id})
    public function show(Request $request, $id) {
        $pet = Pet::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$pet) {
            return response()->json([
                'message' => 'Data hewan tidak ditemukan atau hak akses tidak memenuhi',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail hewan peliharaan',
            'data' => $pet
        ]);
    }

    //function: memperbarui data hewan (PATCH /api/v1/pets/{id})
    public function update(Request $request, $id) {
        $pet = Pet::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$pet) {
            return response()->json([
                'message' => 'Data hewan tidak ditemukan atau hak akses tidak memenuhi'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'species' => 'sometimes|required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|',
            'special_notes' => 'string|string|',
        ]);

        $pet->update($request->only([
            'name', 'species', 'breed', 'age', 'special_notes'
        ]));

        return response()->json([
            'message' => 'Data hewan berhasil diupdate',
            'data' => $pet
        ]);
    }

    //function: menghapus data hewan (DELETE /api/v1/pets/{id})
    public function destroy(Request $request, $id) {
        $pet = Pet::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$pet) {
            return response()->json([
                'message' => 'Gagal menemukan data hewan atau hak akses tidak mencukupi'
            ], 404);
        }

        $pet->delete();

        return response()->json([
            'message' => 'Data hewan berhasil dihapus'
        ]);
    }
}


