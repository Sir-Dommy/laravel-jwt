<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class TestController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login','register']]);
    // }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');
    
            // return($credentials);
            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
    
            $user = Auth::user();
            return response()->json([
                    'status' => 'success',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
        }
        catch(\Exception $e){
            return $e->getMessage();
        }
        

    }

    public function register(Request $request){
        try{
            DB::beginTransaction();
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:3',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = Auth::login($user);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
        
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out', 
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    //test function
    public function test(){
        // Create roles
        $user = User::find(User::getUserId());
        $role = Role::findByName('admin'); // Retrieve the admin role
        $permission = Permission::findByName('edit_users'); // Retrieve the permission

        $user->assignRole($role); // Assign the admin role to the user
        $user->givePermissionTo($permission);

        if($user->can('edit_users')){
            echo ("Allowed, 200");
        }
        if($user->hasRole('admin')){
            echo ("Allowed, 200");
        }
        else{
            return ("Not Allowed, 401");
        }
        
    }

    public function generateToken(){
        try{
            $user = User::find(User::getUserId()); 
            return $user;
        }
        catch(\Exception $e){
            return $e->getMessage();
        }
        
    }
}
