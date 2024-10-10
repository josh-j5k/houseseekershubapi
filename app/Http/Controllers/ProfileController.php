<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Helpers\ImageCompressHelper;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Dashboard/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $user_id = $request->user()->id;
        $current_avatar = User::where('id', $request->user()->id)->value('avatar');
        if (is_dir(public_path($current_avatar))) {

            unlink(public_path($current_avatar));
        }
        $folder = date("Y");
        $subFolders = date("m");
        $image_compresser = new ImageCompressHelper($request->avatar[0], 100, 100, $folder, $subFolders, 'avatars');
        $avatar = $image_compresser->compress();
        User::where('id', $request->user()->id)->update(['avatar' => $avatar]);

        return Redirect::route('user.profile.edit', $user_id);
    }
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user_id = $request->user()->id;

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();


        return Redirect::route('user.profile.edit', $user_id);
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
