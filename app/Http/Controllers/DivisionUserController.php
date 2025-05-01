<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
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
            'users' => $users,
        ]);
    }
    
    
    
}
