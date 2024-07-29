<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request; // Impor kelas Request
use Illuminate\Support\Facades\Validator; // Impor kelas Str
use Illuminate\Support\Str;
// Impor kelas Validator

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Kategori',
            'data' => $kategori,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        // Perbaiki validasi
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|unique:kategoris',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $kategori = new Kategori();
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->slug = Str::slug($request->nama_kategori);
            $kategori->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat',
                'data' => $kategori,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
    try{
        $kategori = Kategori::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'detail kategori',
            'data' => $kategori,
        ], 200);

        } catch(\Exception $e) {
           return response()->json([
            'success' => false,
            'message' => 'data tidak ditemukan',
            'errors' => $e->getMessage(),
           ], 404);
        }
    }
}
