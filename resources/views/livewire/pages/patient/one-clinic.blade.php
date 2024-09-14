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
            ->leftjoin(
                "dentist_schedules",
                "users.id",
                "=",
                "dentist_schedules.user_id"
            )
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
                details.acct_status,
                dentist_schedules.sched_status
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
    < x-mary-header size="text-xl md:text-4xl" title="{{ Capitalization::capitalize($clinic['clinic_name'], ' ') . __(' Clinic') }}" separator progress-indicator />
    <div class="w-full space-y-3">
        <iframe class="h-[300px] w-full rounded-md" frameborder="0" src= "https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{ str_replace(',', '', str_replace(' ', '+', $clinic['clinic_address'])) }}' &z=14&output=embed"></iframe>
        <div class="my-2">
            <x-mary-button label="{{ Capitalization::capitalize($clinic['clinic_name'], ' ') }}" link="{{ $clinic['map_link'] }}" external icon-right="o-link" tooltip-buttom="See in google maps" class="btn-ghost font-bold text-xl md:text-4xl" />
        </div>
        <span class="px-4 capitalize text-sm md:text-lg font-semibold md:text-left">{{ $clinic['clinic_address'] }}</span>

        <hr />

        <div class="flex gap-2 md:gap-3">

            @if ($clinic['img_path'] != null)
                <img src="{{ asset('storage/' . $clinic['img_path']) }}" class="md:p-3 rounded-md w-[130px] md:w-[200px]" />
            @else
                <img class="md:p-3 rounded-md w-[130px] md:w-[200px]" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fmedia.istockphoto.com%2Fvectors%2Fno-image-available-sign-vector-id1138179183%3Fk%3D6%26m%3D1138179183%26s%3D612x612%26w%3D0%26h%3DprMYPP9mLRNpTp3XIykjeJJ8oCZRhb2iez6vKs8a8eE%3D&f=1&nofb=1&ipt=519c10e9d0117e99f53c6f64127a6809b46f0ba9a1b4de9034467243cef25496&ipo=images" />
            @endif

            <div class="space-y-3 flex flex-col md:p-3">
                <span class="capitalize text-lg font-semibold">{{ $clinic['first_name'] . ' ' . $clinic['last_name'] . ' ' . Str::charAt($clinic['middle_name'], 0) }}</span>
                <span class="text-md font-semibold">{{ $clinic['email'] }}</span>
                <div class="">
                    @if($clinic['sched_status'] == 0)
                        <x-mary-badge value="Doctor available" class="badge-success" />
                    @elseif ($clinic['sched_status'] == 1)
                        <x-mary-badge value="Doctor is unavailable" class="badge-gray-500 text-white" />
                    @elseif($clinic['sched_status'] == 2)
                        <x-mary-badge value="Doctor is out of office" class="badge-warning" />
                    @else
                        <x-mary-badge value="Doctor is busy" class="badge-error" />
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
