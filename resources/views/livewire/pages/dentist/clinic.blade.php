<?php

use App\Models\DentalClinic;
use Illuminate\Support\Collection;
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

    // operating
    #[Rule("required")]
    public Collection $days;
    #[Rule("required")]
    public string $time_from = "";
    #[Rule("required")]
    public string $time_to = "";

    public function mount(): void
    {
        $this->get_clinics();
    }

    public function show_set_operation(int $id): void
    {
        $this->clinic_id = $id;
        $this->operating_modal = true;
    }

    public function clear_inputs(): void
    {
        $this->clinic_name = "";
        $this->clinic_address = "";
        $this->map_link = "";
        $this->clinic_long = "";
        $this->clinic_lat = "";
        $this->register_modal = false;
    }

    public function get_clinics(): void
    {
        $clinics = DentalClinic::where("user_id", "=", Auth::user()->id)->get();

        if ($clinics) {
            $this->clinics = $clinics;
        } else {
            $this->clinics = null;
        }
    }

    public function register_clinic(): void
    {
        $data = $this->validate();
        $result = DentalClinic::create([
            "user_id" => Auth::user()->id,
            "clinic_name" => Str::of($data["clinic_name"])->lower(),
            "clinic_address" => Str::of($data["clinic_address"])->lower(),
            "map_link" => $data["map_link"],
            "long" => $data["clinic_long"],
            "lat" => $data["clinic_lat"],
        ]);

        if ($result) {
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

    public function set_operating_hours(): void
    {
        return;
    }

    public function clinic_status(): array
    {
        // 0 - unavailable
        // 1 - available
        // 2 - maintenance
        // 3 - close
        // 4 - remove
        return [
            [
                "id" => 0,
                "name" => "Unavailable",
            ],
            [
                "id" => 1,
                "name" => "Available",
            ],
            [
                "id" => 2,
                "name" => "Maintenance",
            ],
            [
                "id" => 3,
                "name" => "Closed",
            ],
            [
                "id" => 4,
                "name" => "Removed",
            ],
        ];
    }

    public function options_days(): array
    {
        return [
            [
                "id" => 1,
                "name" => "Monday",
                "avatar" => "",
            ],
            [
                "id" => 2,
                "name" => "Tuesday",
                "avatar" => "",
            ],
            [
                "id" => 3,
                "name" => "Wednesday",
                "avatar" => "",
            ],
            [
                "id" => 4,
                "name" => "Thursday",
                "avatar" => "",
            ],
            [
                "id" => 5,
                "name" => "Friday",
                "avatar" => "",
            ],
            [
                "id" => 6,
                "name" => "Saturday",
                "avatar" => "",
            ],
            [
                "id" => 7,
                "name" => "Sunday",
                "avatar" => "",
            ],
        ];
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
                                    <x-mary-button icon="iconsax.out-info-circle" link="{{ $clinic['map_link'] }}" external tooltip="Goto google maps" class="btn-circle btn-ghost btn-sm" />
                                @endif
                                <x-mary-button icon="o-clock" tooltip="Set operating hours" class="btn-circle btn-ghost btn-sm" @click="$wire.show_set_operation({{ $clinic['id'] }})" />
                            </x-slot:menu>
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

    <x-mary-modal wire:model="operating_modal" title="{{ __('Set Operating Hours') }}" no-separator>
        <x-mary-form wire:submit="set_operating_hours" no-separator>
            <div class="space-y-4">
                <x-mary-choices-offline
                    label="Operating Days"
                    wire:model="days"
                    :options="$this->options_days()"
                    allow-all
                />
                <div class="grid grid-cols-2 gap-2 items-center">
                    <x-mary-datetime label="Time From" wire:model="time_from" icon="o-calendar" type="time" />
                    <x-mary-datetime label="Time To" wire:model="time_to" icon="o-calendar" type="time" />
                </div>
                <x-select
                    label="Alternative"
                    :options="$users"
                    placeholder="Clinic Status"
                    placeholder-value="0" {{-- Set a value for placeholder. Default is `null` --}}
                    wire:model="$this->clinic_status()" />
            </div>

            <x-slot:actions>
                <x-mary-button label="{{ __('Cancel') }}" @click="$wire.operating_modal = false" />
                <x-mary-button label="{{ __('Confirm') }}" class="btn-primary" type="submit" spinner="set_operating_hours" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>
</div>
