<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(UpdateProfileRequest $request, User $user)
    {
        $user->update($request->validated());

        alert()->success('Success!', 'Your profile has been updated.')->showConfirmButton('Ok', '#2aae61');

        return redirect()->route('profile.edit');
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            alert()->success('Success!', 'Your password has been updated.')->showConfirmButton('Ok', '#2aae61');
        } else {
            $errors['current_password'] = 'Current password is incorrect.';
        }

        return redirect()->route('profile.edit')->withErrors($errors ?? []);
    }
}
