<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Support\TimeRange;

new class extends Component {
    //

    public object|null $doctors = null;

    public function mount(): void
    {
        $result = User::leftjoin(
            "details",
            "users.details_id",
            "=",
            "details.id"
        )
            ->leftjoin("profile_img", "users.id", "=", "profile_img.user_id")
            ->leftjoin(
                "dentist_schedules",
                "users.id",
                "=",
                "dentist_schedules.user_id"
            )
            ->select(
                DB::raw('
                users.email,
                profile_img.img_path,
                profile_img.caption,
                details.first_name,
                details.middle_name,
                details.last_name,
                details.contact_no,
                details.gender,
                details.address,
                dentist_schedules.sched_days,
                dentist_schedules.time_from,
                dentist_schedules.time_to
            ')
            )
            ->where("users.user_role", "=", 2)
            ->get();

        if ($result) {
            $this->doctors = $result;
        }
    }
};
?>

<div class="w-full p-3">
    < x-mary-header size="text-xl md:text-4xl" title="{{ __('Available Doctors') }}" separator progress-indicator />
    <div class="grid md:grid-cols-3 gap-3">
        @if($this->doctors != null)
            @foreach($this->doctors as $doctor)
                <x-mary-card class="col-12 md:col-md" title="{{ Str::of($doctor['first_name'])->ucfirst() . ' ' . Str::of($doctor['middle_name'])->ucfirst() . ' ' . Str::of($doctor['last_name'])->ucfirst() }}">
                    <div class="capitalize font-sm">{{ $doctor['address'] }}</div>
                    <div class="flex flex-col space-y-5 mt-5">
                        <div class="flex flex-col">
                            <p class="capitalize text-md font-bold">Schedule Days</p>
                            <div class="flex gap-2">
                                @foreach(explode(', ', $doctor['sched_days']) as $day)
                                    <x-mary-badge :value="$day" class="badge-primary capitalize" />
                                @endforeach
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="capitalize text-md font-bold">Schedule Time</p>
                            <span class="text-sm font-sm uppercase">{{ TimeRange::stringify($doctor['time_from'], $doctor['time_to']) }}</span>
                        </div>

                        @if($doctor['sched_status'] == 0)
                            <x-mary-badge value="Available" class="badge-success" />
                        @elseif ($doctor['sched_status'] == 1)
                            <x-mary-badge value="Unavailable" class="badge-gray-500 text-white" />
                        @elseif($doctor['sched_status'] == 2)
                            <x-mary-badge value="Out of office" class="badge-warning" />
                        @else
                            <x-mary-badge value="Busy" class="badge-error" />
                        @endif
                    </div>

                    <x-slot:figure>
                        <div class="p-3">
                            @if ($doctor['img_url'] != null)
                                <img src="{{ asset('storage/' . $doctor['img_url']) }}" class="h-56 md:h-full rounded-lg" />
                            @else
                                <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fmedia.istockphoto.com%2Fvectors%2Fno-image-available-sign-vector-id1138179183%3Fk%3D6%26m%3D1138179183%26s%3D612x612%26w%3D0%26h%3DprMYPP9mLRNpTp3XIykjeJJ8oCZRhb2iez6vKs8a8eE%3D&f=1&nofb=1&ipt=519c10e9d0117e99f53c6f64127a6809b46f0ba9a1b4de9034467243cef25496&ipo=images" class="h-56 md:h-full rounded-lg" />
                            @endif
                        </div>
                    </x-slot:figure>
                </x-mary-card>
            @endforeach
        @endif
    </div>
</div>
