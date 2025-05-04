<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DivisionUserController extends Controller
{

    public function userDivisionHome()
    {
        return view('master.userdivision');
    }
    public function getAllDivisionsWithUsers(): JsonResponse
    {
        $divisions = Division::with('users')->get();
    
        $divisionsWithUsers = $divisions->filter(function ($division) {
            return $division->users->isNotEmpty();
        });
    
        $users = $divisionsWithUsers->flatMap(function ($division) {
            return $division->users->map(function ($user) use ($division) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'division_code' => $division->code,
                    'division_name' => $division->name,
                ];
            });
        });
    
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }
    
    /**
     * Store a newly created user in storage
     */
    public function store(Request $request): JsonResponse
    {
        // Debug untuk melihat data yang diterima
        Log::info('User creation request data:', $request->all());
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'division_id' => 'required|exists:divisions,id',
        ]);
    
        if ($validator->fails()) {
            Log::error('User validation failed:', $validator->errors()->toArray());
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'division_id' => (int)$request->division_id, 
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil ditambahkan',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::with('division')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id): JsonResponse
    {
        try {
            $user = User::with('division')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Validate request
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'division_id' => 'required|exists:divisions,id',
            ];

            // Only validate password if provided
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:8';
            }
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->division_id = $request->division_id;
            
            // Only update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil diperbarui',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }
    
}
