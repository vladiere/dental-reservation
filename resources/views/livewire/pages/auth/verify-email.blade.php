<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout("layouts.guest")] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(
                default: route("dashboard", absolute: false),
                navigate: true
            );

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash("status", "verification-link-sent");
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect("/", navigate: true);
    }
};
?>

<div class="h-dvh flex flex-col items-center space-y-3 justify-center">
    <div class="mb-4 text-sm md:text-2xl text-center text-wrap text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <x-mary-button wire:click="sendVerification" class="btn-primary text-white" spinner="sendVerification">
        {{ __('Resend Verification Email') }}
    </x-mary-button>

    <x-mary-button wire:click="logout" type="submit" class="btn-ghost btn-sm" spinner="logout">
        {{ __('Log Out') }}
    </x-mary-button>
</div>
