<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request; // Impor kelas Request
use Illuminate\Support\Facades\Validator; // Impor kelas Str
use Illuminate\Support\Str;

// Impor kelas Validator

class TagController extends Controller
{
    public function index()
    {
        $tag = Tag::latest()->get();
        $res = [
            'success' => true,
            'message' => 'Daftar Tag',
            'data' => $tag,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        // Perbaiki validasi
        $validator = Validator::make($request->all(), [
            'nama_tag' => 'required|unique:tags',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tag = new Tag();
            $tag->nama_tag = $request->nama_tag;
            $tag->slug = Str::slug($request->nama_tag);
            $tag->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat',
                'data' => $tag,
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
        try {
            $tag = Tag::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'detail tag',
                'data' => $tag,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Perbaiki validasi
        $validator = Validator::make($request->all(), [
            'nama_tag' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tag = Tag::findOrFail($id);
            $tag->nama_tag = $request->nama_tag;
            $tag->slug = Str::slug($request->nama_tag);
            $tag->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $tag,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return response()->json([
                'success' => true,
                'message' => 'data ' . $tag->nama_tag . ' berhasil dihapus',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}
