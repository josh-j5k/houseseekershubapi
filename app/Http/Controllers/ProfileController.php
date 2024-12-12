<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageCompressHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the user's profile information.
     */
    public function updateAvatar(UpdateAvatarRequest $request)
    {

        try {
            DB::beginTransaction();

            /**
             * @var User
             */
            $user = Auth::user();
            if (Auth::user()->avatar) {
                Storage::disk('public')->delete(Auth::user()->avatar);
            }
            $yearDir = date("Y");
            $monthDir = date("m");
            $dir = "images/$yearDir/$monthDir/avatars";
            $isDir = Storage::disk('public')->exists($dir);
            if ($isDir === false) {
                Storage::disk('public')->makeDirectory($dir);
            }
            $path = Storage::disk('public')->putFile($dir, $request->avatar);
            $user->update(['avatar' => $path]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Avatar updated successfully', config('app.url') . "/$path");
    }
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            if (Auth::user()->email !== $request->email && $request->password === null) {
                throw ValidationException::withMessages(['password' => 'Password is required to change email']);
            }
            $user = User::find(Auth::user()->id);
            if (Auth::user()->email !== $request->email && $request->filled('password')) {
                $request->validate([
                    'password' => ['required', 'current_password'],
                ]);
                if ($request->name !== Auth::user()->name) {
                    $user->update(['name' => $request->name]);
                }
                return $this->response('success', "We have sent confirmation email to $request->email", $user);
            }
            $user->update(['name' => $request->name]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->response('error', $th->getMessage(), statusCode: 406);
        }
        return $this->response('success', 'Information updated successfully', $user);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
