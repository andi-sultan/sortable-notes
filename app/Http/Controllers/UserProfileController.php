<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index()
    {
        $userData = User::where('id', auth()->user()->id)->first()
            ->only(['name', 'email']);
        return view('pages.user_profile', [
            'title' => 'User Profile',
            'data' => $userData
        ]);
    }

    public function updateData(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|max:255'
        ]);

        User::where('id', auth()->user()->id)->update($validatedData);

        return redirect(url('/user-profile'))->with('successUpdateProfile', 'Profile has been updated!');
    }

    public function saveNewPassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8|max:255|different:old_password'
        ]);

        // Match The Old Password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->with("savePasswordError", "Old Password Doesn't match!");
        }

        $newPassword = Hash::make($validatedData['new_password']);

        User::where('id', auth()->user()->id)->update(['password' => $newPassword]);

        return redirect(url('/user-profile'))->with('successUpdatePassword', 'Profile has been updated!');
    }
}
