<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;

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
    public function store(LoginRequest $request)
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

            $user_id = User::where('email', $request->email)->value('id');
            $user = User::find($user_id);

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
    // public function destroy(Request $request): Response
    // {
    //     Auth::guard('web')->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return response()->noContent();
    // }
}
