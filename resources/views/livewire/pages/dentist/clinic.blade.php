<?php

use App\Models\DentalClinic;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public object|null $clinics = null;

    public function mount(): void
    {
        $clinics = DentalClinic::where("user_id", "=", Auth::user()->id)->get();

        if ($clinics) {
            $this->clinics = $clinics;
        } else {
            $this->clinics = null;
        }
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="Clinics" separator progress-indicator />
    @if ($this->clinics == null)
        <div class="">
        </div>
    @else
    @endif
</div>
