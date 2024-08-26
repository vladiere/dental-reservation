<?php

use App\Models\DentalClinic;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

new class extends Component {
    use Toast;

    public object|null $clinics = null;
    public bool $register_modal = false;
    public bool $operating_modal = false;
    public int|null $clinic_id = null;
    public bool $right_drawer = false;
    public bool $left_drawer = false;

    #[Rule("required|string|max:255")]
    public string $clinic_name = "";
    #[Rule("required|string|max:255")]
    public string $clinic_address = "";
    #[Rule("nullable|string|max:255")]
    public string|null $map_link = "";
    #[Rule("nullable|string|max:255")]
    public string|null $clinic_long = "";
    #[Rule("nullable|string|max:255")]
    public string|null $clinic_lat = "";

    public function mount(): void
    {
        $this->get_clinics();
    }

    public function show_set_operation(int $id): void
    {
        $this->clinic_id = $id;
        $this->right_drawer = true;
    }

    public function show_schedules(int $id): void
    {
        $this->clinic_id = $id;
        $this->left_drawer = true;
    }

    public function clear_inputs(): void
    {
        $this->clinic_name = "";
        $this->clinic_address = "";
        $this->map_link = "";
        $this->clinic_long = "";
        $this->clinic_lat = "";
        $this->clinic_status = "";
        $this->register_modal = false;
    }

    public function get_clinics(): void
    {
        $clinics = DentalClinic::where("user_id", "=", Auth::user()->id)->get();

        if ($clinics) {
            $this->clinics = $clinics;
        }
    }

    public function register_clinic(): void
    {
        $data = $validate();
        $result = DentalClinic::create([
            "user_id" => Auth::user()->id,
            "clinic_name" => Str::of($data["clinic_name"])->lower(),
            "clinic_address" => Str::of($data["clinic_address"])->lower(),
            "map_link" => $data["map_link"],
            "long" => $data["clinic_long"],
            "lat" => $data["clinic_lat"],
        ]);

        if ($result) {
            $this->reset();
            $this->clear_inputs();
            $this->get_clinics();
            $this->success(
                "Dental Clinic added successfully.",
                position: "toast-top top-right"
            );
        } else {
            $this->warning(
                "Add dental clinic failed.",
                position: "toast-top top-right"
            );
        }
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="{{ __('Clinics') }}" separator progress-indicator />
    @if (count($clinics) > 0)
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <x-mary-button label="{{ __('Add clinic') }}" type="button" class="btn-primary rounded-md text-white" @click="$wire.register_modal = true" />
                <div class="mt-5 w-full grid grid-cols-3 gap-5">
                    @foreach($clinics as $clinic)
                        <x-mary-card title="{{ Str::of($clinic['clinic_name'])->ucfirst() }}">
                            {{ Str::of($clinic['clinic_address'])->ucfirst() }}
                            <x-slot:figure>
                                <iframe width="100%" height="170" frameborder="0" src= "https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{ str_replace(',', '', str_replace(' ', '+', $clinic['clinic_address'])) }}' &z=14&output=embed"></iframe>
                            </x-slot:figure>
                            <x-slot:menu>
                                @if ($clinic['map_link'] != null)
                                    <x-mary-button icon="o-globe-alt" link="{{ $clinic['map_link'] }}" external tooltip="Goto google maps" class="btn-circle btn-ghost btn-sm" />
                                @endif
                                <x-mary-button icon="o-clock" tooltip="Set operating hours" class="btn-circle btn-ghost btn-sm" @click="$wire.show_set_operation({{ $clinic['id'] }})" />
                            </x-slot:menu>
                            <x-mary-button icon="iconpark.schedule-o" tooltip="Check dental schedules" class="btn-circle btn-ghost btn-sm" @click="$wire.show_schedules({{ $clinic['id'] }} )" />
                        </x-mary-card>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-3">
                <p class="mt-2 font-medium text-xl text-gray-800 dark:text-white">
                    {{ __('Your clinic is not yet registered. Register your clinic now!') }}
                </p>
                <x-mary-button label="{{ __('Register clinic') }}" type="button" class="btn-primary rounded-md text-white" @click="$wire.register_modal = true" />
            </div>
        </div>
    @endif

    <x-mary-modal wire:model="register_modal" title="{{ $clinics == null ? __('Clinic registration') : __('Add clinic') }}" no-separator>
        <x-mary-form wire:submit="register_clinic" no-separator>
            <div class="space-y-4">
                <x-mary-input  class="rounded-lg" label="{{ __('Clinic Name') }}" wire:model="clinic_name" required />
                <x-mary-input  class="rounded-lg" label="{{ __('Complete Address') }}" wire:model="clinic_address" required />
                <x-mary-input  class="rounded-lg" label="{{ __('Google map link') }}" hint="(Optional) since it has address" wire:model="map_link" />
                <x-mary-input  class="rounded-lg" label="{{ __('Map longitude') }}" hint="(Optional) longitude for google map" wire:model="clinic_long" />
                <x-mary-input  class="rounded-lg" label="{{ __('Map latitude') }}" hint="(Optional) latitude for the google map" wire:model="clinic_lat" />
            </div>

            <x-slot:actions>
                <x-mary-button label="{{ __('Cancel') }}" @click="$wire.register_modal = false" />
                <x-mary-button label="{{ __('Confirm') }}" class="btn-primary" type="submit" spinner="register_clinic" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>

    <x-mary-drawer wire:model="right_drawer" class="w-11/12 lg:w-1/3" right>
        <div class="text-lg font-bold">Set clinic operating hours</div>
        @if ($clinic_id != null)
            <livewire:pages.dentist.components.set-schedules :clinic_id="$clinic_id" />
        @endif
    </x-mary-drawer>

    <x-mary-drawer wire:model="left_drawer" class="w-11/12 lg:w-1/3">
        <div>Clinic schedules</div>
        @if($clinic_id != null)
            <livewire:pages.dentist.components.get-schedules :clinic_id="$clinic_id" />
        @endif
        <div class="flex justify-between items-center">
            <x-mary-button label="Close" @click="$wire.left_drawer = false" />
            <x-mary-button class="btn-primary text-white" label="Clinic Services" link="{{ route('clinic_service', ['id' => $clinic_id]) }}" />
        </div>
    </x-mary-drawer>
</div>
