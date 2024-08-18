<?php

use App\Models\DentalClinic;
use Mary\Traits\Toast;

use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public object|null $schedule = null;

    public function mount(int $clinic_id): void
    {
        $this->schedule = DentalClinic::rightjoin(
            "schedules",
            "dental_clinic.id",
            "=",
            "schedules.dental_clinic_id"
        )
            ->where("dental_clinic.id", "=", $clinic_id)
            ->get();
    }
};
?>

<div class="space-y-4">
    {{ $schedule }}
    <!-- <div class="text-lg font-bold">{{ $schedule['clinic_name'] }}</div>
    <iframe width="100%" height="170" frameborder="0" src= "https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{ str_replace(',', '', str_replace(' ', '+', $schedule['clinic_address'])) }}' &z=14&output=embed"></iframe>
    <div class="text-sm font-normal">{{ $schedule['clinic_address'] }}</div>
    <x-mary-button icon="o-globe-alt" link="{{ $clinic['map_link'] }}" external tooltip="Goto google maps" class="btn-circle btn-ghost btn-sm" />
    <div class="space-y-3">
        <div class="text-lg font-bold">Schedule</div>
        @if($schedule['dental_clinic_id'] != null)
            <div class="space-y-2">
                <span class="text-sm">Available Time</span>
                <div class="flex items-center gap-3">
                    <span class="text-sm">FROM:</span>
                    <span class="text-sm">{{ $schedule['time_from'] }}</span>
                    <span class="text-sm">TO:</span>
                    <span class="text-sm">{{ $schedule['time_to'] }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-3 py-1.5 rounded-md bg-secondary">{{ explode(', ', $schedule['available_days']) }}</div>
            </div>
        @else
            <div class="text-lg font-bold text-center">No schedule is set in this clinic.</div>
        @endif
    </div> -->
</div>
