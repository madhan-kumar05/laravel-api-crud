<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Jobs\ProcessUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "status" => "success",
            "data" => User::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|in:Admin,Supervisor,Agent',
            'email' => 'required|email|unique:users,email',
            'location' => 'required|string',
            'date_of_birth' => 'required|date',
            'timezone' => 'required|string|max:255',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $user = User::create($request->all());
        ProcessUser::dispatch($user);
        return response()->json([
            "status" => "success",
            "message" => "User created successfully"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "status" => "error",
                "message" => "User not found"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "status" => "error",
                "message" => "User not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|in:Admin,Supervisor,Agent',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'date_of_birth' => 'sometimes|required|date',
            'timezone' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $user->update($request->all());
        return response()->json([
            "status" => "success",
            "message" => "Updated successfully"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                "status" => "error",
                "message" => "User not found"
            ], 404);
        }

        $user->delete();
        return response()->json([
            "status" => "success",
            "message" => "User deleted successfully"
        ], 200);
    }
}
