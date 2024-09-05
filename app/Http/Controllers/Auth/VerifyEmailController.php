<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(
        EmailVerificationRequest $request
    ): RedirectResponse {
        if ($request->user()->hasVerifiedEmail()) {
            $user = User::find($request()->user()->id);
            $user->user_status = "registered";
            $user->save();
            return redirect()->intended(
                route("dashboard", absolute: false) . "?verified=1"
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            $user = User::find($request()->user()->id);
            $user->user_status = "registered";
            $user->save();
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            route("dashboard", absolute: false) . "?verified=1"
        );
    }
}
