<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout("layouts.guest")] #[Title("Login")] class extends Component {
    public function mount()
    {
        if (empty(request()->query("q"))) {
            $this->redirect(route("register", absolute: false), navigate: true);
        }
    }
};
?>

<div class="h-full w-full flex items-center justify-center">
    <h1 class="text-lg md:text-2xl">{{ __('Registration successfull') }}</h1>
    <span class="text-md md:text-8md">{{ __('Waiting for admin confirmation, we'll send you an email.') }}</span>
</div>
