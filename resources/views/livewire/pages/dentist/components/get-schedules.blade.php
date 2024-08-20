<?php

use App\Models\DentalClinic;
use Mary\Traits\Toast;
use App\Support\TimeRange;
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

        if ($result->isNotEmpty()) {
            $this->schedule = $result[0];
        }
    }
};
?>

<div class="space-y-4 my-5 w-full">
    @if($schedule)
        <div class="text-lg font-bold capitalize">{{ $schedule['clinic_name'] ?? 'N/A' }}</div>
        <div class="text-md font-normal capitalize">{{ $schedule['clinic_address'] ?? 'N/A' }}</div>
        <x-mary-button label="See location" link="{{ $schedule['map_link'] ?? '#' }}" external icon="o-link" tooltip="See location in google maps" />
        <div class="space-y-3">
            <div class="text-xl font-bold">Schedule</div>
            @if($schedule['dental_clinic_id'])
                <div class="space-y-2">
                    <span class="text-md">Available Time</span>
                    <span class="text-md">{{ TimeRange::stringify($schedule['time_from'] ?? '', $schedule['time_to'] ?? '') }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @foreach(explode(', ', $schedule['available_day'] ?? '') as $day)
                        <div class="px-3 py-1.5 rounded-md bg-accent text-stone-900 capitalize">{{ $day }}</div>
                    @endforeach
                </div>
                <div class="space-y-3 mt-3">
                    <div class="text-xl font-bold">Clinic Status</div>
                    <div class="text-md font-bold uppercase">
                        @if($schedule['clinic_status'] == 0)
                            Unavailable
                        @elseif($schedule['clinic_status'] == 1)
                            Available
                        @elseif($schedule['clinic_status'] == 2)
                            Maintenance
                        @elseif($schedule['clinic_status'] == 3)
                            Closed
                        @else
                            Removed
                        @endif
                    </div>
                </div>
            @else
                <div class="text-lg font-bold text-center">No schedule is set in this clinic.</div>
            @endif
        </div>
    @else
        <div class="text-lg font-bold text-center mt-10">No schedule is set in this clinic.</div>
    @endif
</div>
