<?php

use App\Models\DentalClinic;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public object|null $clinics = null;

    public function mount(): void
    {
        $result = DentalClinic::leftjoin(
            "users",
            "dental_clinic.user_id",
            "=",
            "users.id"
        )
            ->select(
                DB::raw("
                dental_clinic.id as clinic_id,
                users.email,
                dental_clinic.clinic_name,
                dental_clinic.clinic_address,
                dental_clinic.map_link
            ")
            )
            ->get();

        if ($result) {
            $this->clinics = $result;
        }
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="{{ __('Available Clinics') }}" separator progress-indicator />
    @if (count($clinics) > 0)
        <div class="mt-5 w-full grid md:grid-cols-3 gap-5">
            @foreach($clinics as $clinic)
                <x-mary-card title="{{ Str::of($clinic['clinic_name'])->ucfirst() }}">
                    <div class="capitalize">{{ $clinic['clinic_address'] }}</div>
                    <x-slot:figure>
                        <iframe class="h-[300px] w-full rounded-md" frameborder="0" src= "https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{ str_replace(',', '', str_replace(' ', '+', $clinic['clinic_address'])) }}' &z=14&output=embed"></iframe>
                    </x-slot:figure>
                    <x-slot:menu>
                        @if ($clinic['map_link'] != null)
                            <x-mary-button icon="o-globe-alt" link="{{ $clinic['map_link'] }}" external tooltip="Goto google maps" class="btn-circle btn-ghost btn-sm" />
                        @endif
                    </x-slot:menu>
                    <x-slot:actions>
                        <x-mary-button label="Select" class="btn-primary text-white" link="{{ route('one_clinic', ['clinic_id' => $clinic['clinic_id']]) }}" />
                    </x-slot:actions>
                </x-mary-card>
            @endforeach
        </div>
    @endif
</div>
