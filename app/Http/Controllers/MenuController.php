<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

use App\Traits\UploadGambarTrait;

use App\Models\Menu;

class MenuController extends Controller
{
    use UploadGambarTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrator,waiter');
    }

    public function index()
    {
        // $data = Menu::with('pesanan')->get();
        $data = Menu::all();
        return view('menu.index', compact('data'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_menu' => [
                    'required',
                    'max:255',
                    'string',
                    Rule::unique('menu', 'nama_menu')
                ],
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
                'harga' => 'required|string|max:20',
                'status' => 'required|in:tersedia,habis',
            ]);

            // Process price - remove dots from formatted price
            $harga = (int)str_replace('.', '', $request->input('harga'));

            $gambarPath = $this->uploadFile($request, 'gambar');
            
            Menu::create([
                'nama_menu' => $request->input('nama_menu'),
                'gambar' => $gambarPath ?? 'img/default.jpg',
                'harga' => $harga,
                'status' => $request->input('status'),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Menu added successfully']);
            }
            
            return redirect()->route('menu.index')->with('success', 'Menu added successfully');
        } catch (Exception $e) {
            Log::error('Error saving menu: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 422);
            }
            
            return Redirect::back()->withInput()->with('danger', 'Error saving data: ' . $e->getMessage());
        }
    }

    // public function edit($id)
    // {
    //     $data = Menu::findOrFail($id);
    //     return view('menu.edit', compact('data'));
    // }

    public function update(Request $request, $id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'nama_menu' => [
                    'required',
                    'max:255',
                    Rule::unique('menu', 'nama_menu')->ignore($id, 'idmenu')
                ],
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
                'harga' => 'required|string|max:20',
                'status' => 'required|in:tersedia,habis',
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

            // Process price - remove dots from formatted price
            $harga = (int)str_replace('.', '', $request->input('harga'));

            $data = [
                'nama_menu' => $request->nama_menu,
                'harga' => $harga,
                'status' => $request->status,
            ];

            $gambarPath = $this->uploadFile($request, 'gambar', $menu->gambar);
            if ($gambarPath) {
                $data['gambar'] = $gambarPath;
            }

            $menu->update($data);

            if ($request->expectsJson()) {
                return response()->json(['success' => true,'message' => 'Menu updated successfully']);
            }

            return redirect()->route('menu.index')->with('success', 'Menu updated successfully');
        } catch (Exception $e) {
            Log::error('Error updating menu: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()], 422);
            }
            
            return Redirect::back()->withInput()->with('danger', 'Error updating data: ' . $e->getMessage());
        }
    }

    /**
     * Delete menu item
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $menu = Menu::findOrFail($request->menu_id);

            // save image path
            $gambarPath = $menu->gambar;

            // delete menu from database first
            $deleted = $menu->delete();

            // if successfully deleted from database and it's not the default image
            if ($deleted && $gambarPath != 'img/default.jpg') {
                // convert storage/uploads/filename.jpg to uploads/filename.jpg this is the relative path within the public disk.
                $storagePath = str_replace('storage/', '', $gambarPath);
                // because laravel tries to find the file in the storage/app/public/ and added with the relative path that has been converted to be like this -> storage/app/public/uploads/filename.jpg

                // check if file exists and delete it
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                    Log::info('Image deleted from storage: ' . $storagePath);
                } else {
                    Log::warning('Image not found in storage: ' . $storagePath);
                }
            }
            
            // session()->flash('success', 'Menu berhasil dihapus.');
            return response()->json([
                'success' => true, 
                'message' => 'Menu berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            Log::error('Error saat menghapus menu: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus Data Menu: ' . $e->getMessage()
            ], 500);
        }
    }
}
