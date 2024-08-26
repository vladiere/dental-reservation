<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public object|null $clinic = null;

    public function mount(int $clinic_id): void
    {
        $result = User::leftjoin(
            "dental_clinic",
            "users.id",
            "=",
            "dental_clinic.user_id"
        )
            ->leftjoin("details", "users.details_id", "=", "details.id")
            ->leftjoin(
                "schedules",
                "dental_clinic.id",
                "=",
                "schedules.dental_clinic_id"
            )
            ->select(
                DB::raw("
                schedules.id as schedule_id,
                dental_clinic.clinic_name,
                dental_clinic.clinic_address,
                dental_clinic.map_link,
                details.first_name,
                details.middle_name,
                details.last_name,
                users.email,
                schedules.available_day,
                schedules.time_from,
                schedules.time_to,
                schedules.clinic_status
            ")
            )
            ->where("dental_clinic.id", "=", $clinic_id)
            ->get();

        if ($result->isNotEmpty()) {
            $this->clinic = $result[0];
        }
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="{{ __('Available Clinics') }}" separator progress-indicator />
    {{ $clinic }}
    <div class="w-full space-y-3">
        <iframe class="h-10/12 w-full rounded-md" frameborder="0" src= "https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{ str_replace(',', '', str_replace(' ', '+', $clinic['clinic_address'])) }}' &z=14&output=embed"></iframe>
        <div class="font-bold text-2xl capitalize">{{ $clinic['clinic_name'] }}</div>
        <span class="capitalize text-sm font-semibold">{{ $clinic['clinic_address'] }}</span>
    </div>
</div>
