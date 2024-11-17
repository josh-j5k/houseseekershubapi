<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\HttpFoundation\Response as Res;

class AuthenticatedSessionController extends Controller
{
    use HasApiTokens;
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {

        try {
            DB::beginTransaction();
            if (

                !Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password,
                ])
            ) {
                throw new Exception('Invalid Credentials');
            }

            $user = User::where('email', $request->email)->firstOr();

            $token = $user->createToken('access_token');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], Res::HTTP_NOT_ACCEPTABLE);
        }
        return response()->json(['user' => $user, 'access_token' => $token->plainTextToken, 'message' => 'Login successful'], Res::HTTP_ACCEPTED);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::user()->revoke;

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
