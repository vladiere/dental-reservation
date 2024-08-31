<?php

use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public int $clinic_id = 0;
    public object|null $schedules = null;
    public object|null $services = null;

    #[Rule("required")]
    public string $reserve_type = "";
    #[Rule("required")]
    public int $service_id;
    #[Rule("required")]
    public int $patient_count = 1;

    public function mount(int $clinic_id): void
    {
        $this->clinic_id = $clinic_id;

        $this->fetchClinic();
    }

    public function fetchClinic(): void
    {
        $result = Service::select(
            DB::raw("
            id as serv_id,
            service_price as serv_price,
            service_name as serv_name,
            service_description as serv_desc
        ")
        )
            ->where("dental_clinic_id", "=", $this->clinic_id)
            ->get();
        $result1 = Schedule::select(
            DB::raw("
            id as sched_id,
            available_day as sched_days,
            time_to,
            time_from
        ")
        )
            ->where("dental_clinic_id", "=", $this->clinic_id)
            ->get();

        $this->services = $result;
        $this->schedules = $result1;
    }

    public function resrv_type(): array
    {
        return [
            ["id" => "clustered", "name" => "Clustered"],
            ["id" => "solo", "name" => "Solo"],
        ];
    }

    public function set_appointment(): void
    {
        $this->validate();
        return;
    }
};
?>

<div class="w-full space-y-3">
    < x-mary-header size="text-xl md:text-4xl" title="{{ __('Set clinic appointment') }}" separator progress-indicator />
    <x-mary-form wire:submit.prevent="set_appointment" no-separator class="h-full mx-auto w-full md:w-4/5 mt-5">
        <div class="grid md:grid-cols-3 space-y-2 md:space-y-0 md:gap-3">
            <x-mary-datetime label="Set reservation date and time" icon="o-calendar" type="datetime-local" hint="After date then type your desired time" />
            <x-mary-select label="Services" :options="$this->services" option-value="serv_id" option-label="serv_name" icon="eos.service" placeholder="Select a service you want" wire:model="service_id" />
            <x-mary-select
                label="Master user"
                icon="o-user"
                :options="$this->resrv_type()"
                placeholder="Select appointment type"
                wire:model.live="reserve_type"
            />
            @if($this->reserve_type == "clustered")
                <x-mary-input type="number" min="1" max="999" label="How many patients" placeholder="Enter patients count" icon="fluentui.people-32" hint="Enter of how many people are with you." wire:model="patient_count" />
            @endif
        </div>
        <x-slot:actions>
            <x-mary-button label="Submit" class="btn-primary text-white" type="submit" spinner="set_appointment" />
        </x-slot:actions>
    </x-mary-form>
</div>
