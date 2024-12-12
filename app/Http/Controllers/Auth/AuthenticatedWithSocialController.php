<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticateWithSocialRequest;

class AuthenticatedWithSocialController extends Controller
{
    public function store(AuthenticateWithSocialRequest $request)
    {
        try {
            DB::beginTransaction();
            /**
             * @var User
             */
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = User::Create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'provider' => $request->provider,
                    'provider_id' => $request->provider_id,
                    'avatar' => $request->avatar,
                    'email_verified_at' => $request->email_verified ? now() : null,
                    'ref' => Str::uuid(),
                ]);
            }

            $token = $user->createToken('access_token');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('error', 'successfully login', ['user' => ['name' => $user->name, 'email' => $user->email, 'ref' => $user->ref, 'picture' => $user->avatar], 'access_token' => $token->plainTextToken], statusCode: 202);
    }
}
