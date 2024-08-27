<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use App\Support\Capitalization;
use Illuminate\Support\Str;
use App\Support\TimeRange;

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
            ->leftjoin("profile_img", "users.id", "=", "profile_img.user_id")
            ->select(
                DB::raw("
                dental_clinic.id as clinic_id,
                schedules.id as schedule_id,
                profile_img.img_path,
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
                schedules.clinic_status,
                details.acct_status
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
        <iframe class="h-[300px] w-full rounded-md" frameborder="0" src= "https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{ str_replace(',', '', str_replace(' ', '+', $clinic['clinic_address'])) }}' &z=14&output=embed"></iframe>
        <div class="my-2">
            <x-mary-button label="{{ Capitalization::capitalize($clinic['clinic_name'], ' ') }}" link="{{ $clinic['map_link'] }}" external icon-right="o-link" tooltip="See in google maps" class="btn-ghost font-bold text-xl md:text-4xl" />
        </div>
        <span class="px-4 capitalize text-sm md:text-md font-semibold md:text-left">{{ $clinic['clinic_address'] }}</span>

        <hr />

        <div class="flex gap-2 md:gap-3">
            <img src="{{ asset('storage/' . $clinic['img_path']) }}" class="md:p-3 rounded-md w-[200px]" />
            <div class="space-y-3 flex flex-col md:p-3">
                <span class="capitalize text-md font-semibold">{{ $clinic['first_name'] . ' ' . $clinic['last_name'] . ' ' . Str::charAt($clinic['middle_name'], 0) }}</span>
                <span class="text-md font-semibold">{{ $clinic['email'] }}</span>
                <div class="">
                    @if($clinic['acc_status'] == 0)
                        <x-mary-badge value="Available" class="badge-success" />
                    @else
                        <x-mary-badge value="Unavailable" class="badge-warning" />
                    @endif
                </div>
                <div class="space-y-3 flex flex-col">
                    <span class="text-md font-semibold">Open {{ TimeRange::stringify($clinic['time_from'] ?? '', $clinic['time_to'] ?? '') }}</span>
                    <x-mary-button label="Set reservation" icon="bx.calendar-edit" class="btn-primary text-white btn-sm" link="{{ route('clinic_reservation', ['clinic_id' => $clinic['clinic_id']]) }}" />
                </div>
            </div>
        </div>
    </div>
</div>
