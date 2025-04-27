<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrator');
    }

    public function index()
    {
        $data = User::all();
        return view('users.index', compact('data'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'username' => [
                    'max:255',
                    Rule::unique('users', 'username'),
                ],
                'role' => 'in:administrator,waiter,kasir,owner',
                'password' => 'required|min:5|max:32', // password is required for creation
            ];

            $validator = Validator::make($request->all(), $rules);

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
                'username' => $request->input('username'),
                'role' => $request->input('role'),
                'password' => Hash::make($request->input('password')), // Hash the password
            ];

            $user = User::create($data);

            if ($request->expectsJson()) {
                return response()->json(['success' => true,'message' => 'User created successfully']);
            }

            return redirect()->route('user.index')->with('success', 'Selamat, Data user Berhasil di Tambahkan');
        } catch (Exception $e) {
            Log::error('Error creating User: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error creating data: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->withInput()->with('danger', 'Terjadi kesalahan saat menambahkan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $rules = [
                'username' => [
                    'max:255',
                    Rule::unique('users', 'username'),
                ],
                'role' => 'in:administrator,waiter,kasir,owner',
            ];

            // add password validation only if a new password is provided
            if ($request->filled('password')) {
                $rules['password'] = 'min:5|max:32';
            }

            $validator = Validator::make($request->all(), $rules);

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
                'username' => $request->input('username'),
                'role' => $request->input('role'),
            ];

            // hash the password only if a new password is provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->input('password'));
            }

            $user->update($data);

            if ($request->expectsJson()) {
                return response()->json(['success' => true,'message' => 'User updated successfully']);
            }

            return redirect()->route('user.index')->with('success', 'Selamat, Data user Berhasil di Update');
        } catch (Exception $e) {
            Log::error('Error updating User: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()], 422);
            }

            return Redirect::back()->withInput()->with('danger', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $user = User::findOrFail($request->iduser);
            $user->delete();
            
            // session()->flash('success', 'User berhasil dihapus.');
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.'], 200);
        } catch (Exception $e) {
            Log::error('Error saat menghapus User: ' . $e->getMessage());
            // session()->flash('error', 'Gagal menghapus user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus Data User: ' . $e->getMessage()], 500);
        }
    }
}
