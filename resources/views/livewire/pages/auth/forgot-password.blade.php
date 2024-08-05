<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new #[Layout("layouts.guest")] class extends Component {
    use Toast;
    public string $email = "";

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            "email" => ["required", "string", "email"],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only("email"));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError("email", __($status));

            return;
        }

        $this->reset("email");

        session()->flash("status", __($status));
        $this->toast(
            type: "success",
            title: "E-mail sent",
            description: $status, // optional (text)
            position: "toast-top toast-end", // optional (daisyUI classes)
            icon: "o-information-circle", // Optional (any icon)
            css: "alert-success", // Optional (daisyUI classes)
            timeout: 3000 // optional (ms)
        );
    }
};
?>

<div class="w-full p-2 sm:p-0 sm:w-3/5 md:w-96 mx-auto mt-20">
    <div class="mb-4 text-sm ">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-mary-form wire:submit="sendPasswordResetLink">
        <!-- Email Address -->
        <x-mary-input  class="rounded-lg" label="{{ __('E-mail') }}" wire:model="email" icon="o-envelope" inline />

        <x-slot:actions>
            <x-mary-button label="{{ __('Email Password Reset Link') }}" type="submit" class="btn-primary rounded-md text-white" spinner="sendPasswordResetLink" />
        </x-slot:actions>
    </x-mary-form>
</div>
