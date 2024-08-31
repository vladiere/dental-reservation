<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Illuminate\Support\Str;

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
                details.acct_status
            ')
            )
            ->where("users.role", "=", "dentist")
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

                    <x-slot:figure>
                        <div class="p-3">
                            <img src="{{ asset('storage/' . $doctor['img_path']) }}" class="h-56 md:h-full rounded-lg" />
                        </div>
                    </x-slot:figure>
                </x-mary-card>
            @endforeach
        @endif
    </div>
</div>
