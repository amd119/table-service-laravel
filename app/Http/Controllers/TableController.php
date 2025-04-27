<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

use App\Models\Meja;

class TableController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrator');
    }

    public function index()
    {
        // $data = Menu::with('pesanan')->get();
        $data = Meja::all();
        return view('meja.index', compact('data'));
    }

    public function create()
    {
        // $data = KategoriBuku::all();
        return view('meja.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nomor' => [
                    'required',
                    'max:255',
                    Rule::unique('meja', 'nomor')
                ],
                'kapasitas' => 'required|numeric|max:100',
                'status' => 'required|in:tersedia,terisi,reserved',
            ]);

            Meja::create([
                'nomor' => $request->input('nomor'),
                'kapasitas' => (int)$request->input('kapasitas'),
                'status' => $request->input('status'),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Selamat, Meja Berhasil di Tambahkan']);
            }

            return redirect()->route('table.index')->with('success', 'Selamat, Meja Berhasil di Tambahkan');
        } catch (Exception $e) {
            Log::error('Error saat menyimpan meja: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->withInput()->with('danger', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // public function edit($id)
    // {
    //     $data = Meja::findOrFail($id);
    //     return view('meja.edit', compact('data'));
    // }

    public function update(Request $request, $id)
    {
        try {
            $meja = Meja::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nomor' => [
                    'required',
                    'max:255',
                    Rule::unique('meja', 'nomor')
                ],
                'kapasitas' => 'required|numeric|max:100',
                'status' => 'required|in:tersedia,terisi,reserved',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Validation error occurred', 
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = [
                'nomor' => $request->input('nomor'),
                'kapasitas' => (int)$request->input('kapasitas'),
                'status' => $request->input('status'),
            ];

            $meja->update($data);

            if ($request->expectsJson()) {
                return response()->json(['success' => true,'message' => 'Table updated successfully']);
            }

            return redirect()->route('menu.index')->with('success', 'Selamat, Data meja Berhasil di Update');
        } catch (Exception $e) {
            Log::error('Error updating table: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->withInput()->with('danger', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $meja = Meja::findOrFail($request->idmeja);
            $meja->delete();
            
            // session()->flash('success', 'Meja berhasil dihapus.');
            return response()->json(['success' => true, 'message' => 'Meja berhasil dihapus.']);
        } catch (Exception $e) {
            Log::error('Error saat menghapus meja: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus Data meja: ' . $e->getMessage()], 500);
        }
    }
}
