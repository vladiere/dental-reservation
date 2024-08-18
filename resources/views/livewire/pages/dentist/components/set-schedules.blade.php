<?php

use App\Models\Schedule;
use Mary\Traits\Toast;

use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public $clinic_id;

    // operating
    #[Rule("required|array|max:255")]
    public array $days = [];
    #[Rule("required|string|max:255")]
    public string $time_from = "";
    #[Rule("required|string|max:255")]
    public string $time_to = "";
    #[Rule("required|string|max:255")]
    public string $clinic_status = "";

    /**
    "dental_clinic_id",
        "available_day",
        "time_from",
        "time_to",
        "doctor_status",
        "clinic_status",
    */

    public function set_operating_hours(): void
    {
        $data = $this->validate();
        $result = Schedule::create([
            "dental_clinic_id" => $this->clinic_id,
            "available_day" => collect($data["days"])->implode(", "),
            "time_from" => $data["time_from"],
            "time_to" => $data["time_to"],
            "doctor_status" => 0,
            "clinic_status" => $data["clinic_status"],
        ]);

        if ($result) {
            $this->reset();
            $this->success(
                "Clinic Schedule set successful",
                position: "toast-top toast-right"
            );
        } else {
            $this->warning(
                "Setting clinic schedules error.",
                position: "toast-top top-right"
            );
        }
    }

    public function status(): array
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

    public function option_days(): array
    {
        return [
            [
                "id" => "monday",
                "name" => "Monday",
            ],
            [
                "id" => "tuesday",
                "name" => "Tuesday",
            ],
            [
                "id" => "wednesday",
                "name" => "Wednesday",
            ],
            [
                "id" => "thursday",
                "name" => "Thursday",
            ],
            [
                "id" => "friday",
                "name" => "Friday",
            ],
            [
                "id" => "saturday",
                "name" => "Saturday",
            ],
            [
                "id" => "sunday",
                "name" => "Sunday",
            ],
        ];
    }
};
?>

<x-mary-form wire:submit="set_operating_hours" no-separator>
    <div class="space-y-4">
        <x-mary-choices label="Select days schedules" wire:model="days" :options="$this->option_days()" allow-all />
        <div class="grid grid-cols-2 gap-2 items-center">
            <x-mary-datetime label="Time From" wire:model="time_from" icon="o-calendar" type="time" />
            <x-mary-datetime label="Time To" wire:model="time_to" icon="o-calendar" type="time" />
        </div>
        <x-mary-select
            label="Set clinic status"
            :options="$this->status()"
            placeholder="Clinic Status"
            placeholder-value="0"
            wire:model="clinic_status"
        />
    </div>

    <x-slot:actions>
        <x-mary-button label="{{ __('Cancel') }}" @click="$wire.right_drawer = false" />
        <x-mary-button label="{{ __('Confirm') }}" class="btn-primary" type="submit" spinner="set_operating_hours" />
    </x-slot:actions>
</x-mary-form>
