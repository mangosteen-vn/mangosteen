<?php

namespace Mangosteen\User\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mangosteen\Models\Entities\Role;
use Mangosteen\Models\Entities\User;


/**
 *
 */
class AuthController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function handleLoginWithFirebase(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $email_verified = $request->get('email_verified');
        $avatar = $request->get('avatar');
        $uid = $request->get('uid');
        $provider = $request->get('provider');
        $password = $request->get('password');
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password !== '' ? $password : Str::random(2003)),
                    'email_verified' => $email_verified,
                    'avatar' => $avatar,
                ]);
            }
            if ($provider === 'google.com' && !$user->google_uid) {
                $user->update(['google_uid' => $uid]);
            }
            if ($provider === 'facebook.com' && !$user->facebook_uid) {
                $user->update(['facebook_uid' => $uid]);
            }
            if ($provider === 'password' && !$user->password_uid) {
                $user->update(['password_uid' => $uid]);
            }
            if ($request->has('name') && !$user->name) {
                $user->update(['name' => $name]);
            }
            if ($request->has('email_verified') && !$user->email_verified) {
                $user->update(['email_verified' => $email_verified]);
            }
            if ($request->has('avatar') && !$user->avatar) {
                $user->update(['avatar' => $avatar]);
            }
            if (!$user->roles()->exists()) {
                $this->attachUserRole($user);
            }

            $token = auth()->login($user);

            return $this->respondWithToken($token);
        } catch (Exception $e) {
            throw $e;
//            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * @param $user
     * @return void
     */
    protected function attachUserRole($user): void
    {
        $userRole = Role::where('slug', 'user')->firstOrFail();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }
    }

    /**
     * @param $user
     * @return void
     */
    protected function attachAdminRole($user): void
    {
        $userRole = Role::where('slug', 'admin')->firstOrFail();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }
    }

    /**
     * @param $user
     * @return void
     */
    protected function attachSuperAdminRole($user): void
    {
        $userRole = Role::where('slug', 'super admin')->firstOrFail();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }
    }

    public function checkAdminRole()
    {
        $user = auth()->user();

        $adminRoles = ['admin', 'super admin'];
        $userRoles = $user->roles->pluck('slug')->toArray();

        $isAdmin = array_intersect($adminRoles, $userRoles) !== [];

        return response()->json(['isAdmin' => $isAdmin]);
    }

}
