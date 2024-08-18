<?php

use App\Models\DentalClinic;
use Mary\Traits\Toast;

use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public object|null $schedule = null;

    public function mount(int $clinic_id): void
    {
        $result = DentalClinic::rightjoin(
            "schedules",
            "dental_clinic.id",
            "=",
            "schedules.dental_clinic_id"
        )
            ->where("dental_clinic.id", "=", $clinic_id)
            ->get();

        $this->schedule = $result[0];
    }
};
?>

<div class="space-y-4">
    <div class="text-lg font-bold">{{ $schedule['clinic_name'] }}</div>
    <div class="text-sm font-normal">{{ $schedule['clinic_address'] }}</div>
    <x-mary-button icon="o-globe-alt" link="{{ $schedule['map_link'] }}" external tooltip="Goto google maps" class="btn-circle btn-ghost btn-sm" />
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
    </div>
</div>
