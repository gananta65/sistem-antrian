<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Staff login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff || !Hash::check($request->password, $staff->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Mark staff as active
        $staff->update(['is_active' => true]);

        // Create token
        $token = $staff->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'counter_number' => $staff->counter_number,
                'is_active' => $staff->is_active,
                'total_served' => $staff->total_served,
            ],
        ]);
    }

    /**
     * Staff logout
     */
    public function logout(Request $request)
    {
        $staff = $request->user();
        
        // Mark staff as inactive
        $staff->update([
            'is_active' => false,
            'current_queue_id' => null,
        ]);

        // Delete current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get current staff info
     */
    public function me(Request $request)
    {
        $staff = $request->user();
        $staff->load('currentQueue');

        return response()->json([
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'counter_number' => $staff->counter_number,
                'is_active' => $staff->is_active,
                'total_served' => $staff->total_served,
                'current_queue' => $staff->currentQueue,
            ],
        ]);
    }
}