<?php

use App\Livewire\Actions\Logout;
use App\Models\Details;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    protected string $fullname = "";
    protected string $email = "";

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect("/", navigate: true);
    }

    public function mount()
    {
        $details = Details::find(Auth::user()->id);
        $this->email = Auth::user()->email;
        $this->fullname = $details->first_name . " " . $details->last_name;
    }

    public function user_details()
    {
        return Details::with("user")
            ->where("id", Auth::user()->details_id)
            ->first();
    }
};
?>

{{-- MENU --}}
<x-mary-menu activate-by-route>

    <x-mary-menu-separator />

    @if(auth()->user()->role === 'admin')
        <x-mary-menu-item title="Dashboard" icon="o-home" link="{{ route('admin_dashboard') }}" />
        <x-item_icon.i-i-admin />
    @elseif (auth()->user()->role === 'subadmin')
        <x-mary-menu-item title="Dashboard" icon="o-home" link="{{ route('subadmin_dashboard') }}" />
        <x-item_icon.i-i-subadmin />
    @elseif (auth()->user()->role === 'dentist')
        <x-mary-menu-item title="Dashboard" icon="o-home" link="{{ route('dentist_dashboard') }}" />
        <x-item_icon.i-i-dentist />
    @else
        <x-mary-menu-item title="Dashboard" icon="o-home" link="{{ route('patient_dashboard') }}" />
        <x-item_icon.i-i-patient />
    @endif
    <x-mary-menu-sub title="Settings" icon="o-cog-6-tooth">
        <x-mary-menu-item title="Theme" icon="o-sun" darkTheme="sunset" lightTheme="corporate" @click="$dispatch('mary-toggle-theme')" />
        @if(auth()->user()->role === 'admin')
            <x-mary-menu-item title="Profile" icon="s-user-circle" link="{{ route('admin_profile') }}" />
        @elseif (auth()->user()->role === 'subadmin')
            <x-mary-menu-item title="Profile" icon="s-user-circle" link="{{ route('subadmin_profile') }}" />
        @elseif (auth()->user()->role === 'dentist')
            <x-mary-menu-item title="Profile" icon="s-user-circle" link="{{ route('dentist_profile') }}" />
        @else
            <x-mary-menu-item title="Profile" icon="s-user-circle" link="{{ route('patient_profile') }}" />
        @endif
        <x-mary-menu-item title="Logout" icon="o-power" wire:click="logout" />
    </x-mary-menu-sub>
</x-mary-menu>