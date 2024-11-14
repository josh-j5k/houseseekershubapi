<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Laravel\Sanctum\HasApiTokens;
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

            if (
                !Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password,
                ])
            ) {
                throw new Exception('Invalid Credentials');
            }
            $request->session()->regenerate();
            $user = User::select(['ref', 'email', 'name'])->where('email', $request->email)->get();

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Res::HTTP_NOT_ACCEPTABLE);
        }
        return response()->json(['user' => $user[0], 'message' => 'Login successful'], Res::HTTP_ACCEPTED);
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
