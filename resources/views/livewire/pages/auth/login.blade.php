<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout("layouts.guest")] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(
            default: route("dashboard", absolute: false),
            navigate: true
        );
    }
};
?>

<div class="w-full p-2 md:p-0 sm:w-3/4 md:w-96 mx-auto mt-20">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-mary-form wire:submit="login">
        <!-- Email Address -->
        <span class="mb-2 font-bold text-lg">Signin to your account.</span>
        <x-mary-input label="{{ __('E-mail') }}" wire:model="form.email" icon="o-envelope" class="rounded-lg" inline />
        <!-- Password -->
        <x-mary-input label="{{ __('Password') }}" wire:model="form.password" type="password" class="rounded-lg" icon="o-key" inline />

        <!-- Remember Me -->
        <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-2 items-center">
            <x-mary-checkbox label="{{ __('Remember me') }}" wire:model="form.remember" class="rounded-md" />
            @if (Route::has('password.request'))
                <a class="underline text-md text-right text-primary hover:text-secondary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
        <x-slot:actions>
            <x-mary-button label="Create an account" class="btn-ghost" link="/register" class="rounded-lg" />
            <x-mary-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary rounded-lg text-white" spinner="login" />
        </x-slot:actions>
    </x-mary-form>
</div>
