<?php

use App\Models\Details;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string|null $first_name = "";
    public string|null $middle_name = "";
    public string|null $last_name = "";
    public string|null $email = "";
    public string|null $gender = "";

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user_details = Details::find(Auth::user()->details_id);
        $this->first_name = $user_details->first_name ?? "N/A";
        $this->middle_name = $user_details->middle_name ?? "N/A";
        $this->last_name = $user_details->last_name ?? "N/A";
        $this->email = Auth::user()->email ?? "N/A";
        $this->gender = $user_details->gender ?? "N/A";
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty("email")) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch("profile-updated", name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(
                default: route("dashboard", absolute: false)
            );

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash("status", "verification-link-sent");
    }
};
?>

<section class="w-full">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <x-mary-form wire:submit="updateProfileInformation" no-separator class="mt-6 space-y-3 w-full">
        <div class="grid grid-cols-2 gap-3 items-center">
            <x-mary-input  class="rounded-lg" label="First Name" wire:model="last_name" required inline />
            <x-mary-input  class="rounded-lg" label="Middle Name" wire:model="last_name" required inline />
            <x-mary-input  class="rounded-lg" label="Last Name" wire:model="last_name" required inline />
            <x-mary-input readonly class="rounded-lg" label="Gender" wire:model="gender" required inline />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-mary-input  class="rounded-lg" label="E-mail" wire:model="email" required inline />
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="{{ __('Save') }}" type="submit" class="btn-primary rounded-md text-white" spinner="updateProfileInformation" />
        </x-slot:actions>
    </x-mary-form>
</section>
