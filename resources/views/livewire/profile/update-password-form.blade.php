<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = "";
    public string $password = "";
    public string $password_confirmation = "";

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                "current_password" => [
                    "required",
                    "string",
                    "current_password",
                ],
                "password" => [
                    "required",
                    "string",
                    Password::defaults(),
                    "confirmed",
                ],
            ]);
        } catch (ValidationException $e) {
            $this->reset(
                "current_password",
                "password",
                "password_confirmation"
            );

            throw $e;
        }

        Auth::user()->update([
            "password" => Hash::make($validated["password"]),
        ]);

        $this->reset("current_password", "password", "password_confirmation");

        $this->dispatch("password-updated");
    }
};
?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <x-mary-form wire:submit="updatePassword" no-separator class="mt-6 space-y-6">
        <x-mary-input  class="rounded-lg" type="password" icon-right="o-eye" label="Current Password" wire:model="current_password" autocomplete="current-password" required />

        <x-mary-input  class="rounded-lg" type="password" icon-right="o-eye" label="New Password" wire:model="password" autocomplete="new-password" required />

        <x-mary-input  class="rounded-lg" type="password" icon-right="o-eye" label="Confirm Password" wire:model="password_confirmation" required />

        <x-slot:actions>
            <x-mary-button label="{{ __('Save') }}" type="submit" class="btn-primary rounded-md text-white" spinner="updatePassword" />
        </x-slot:actions>
    </x-mary-form>
</section>
