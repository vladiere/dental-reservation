<?php

use App\Models\Reservations;
use Mary\Traits\Toast;

use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public int $clinic_id = 0;
    public object|null $schedules = null;
    public object|null $services = null;

    #[Rule("required")]
    public int $user_id = 0;
    #[Rule("required")]
    public string $reserve_datetime = "";
    #[Rule("required")]
    public string $reserve_type;
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
            ["id" => "cluster", "name" => "Cluster"],
            ["id" => "solo", "name" => "Solo"],
        ];
    }

    public function set_appointment(): void
    {
        $data = $this->validate();
        $data["user_id"] = Auth::user()->id;

        dd($data);
        if ($data["reserve_type"] === "solo" && $data["patient_count"] > 1) {
            $this->error(
                "Patient count is high for solo",
                position: "toast-top toast-right"
            );
        } elseif (
            $data["reserve_type"] === "cluster" &&
            $data["patient_count"] < 2
        ) {
            $this->error(
                "Patient count is low for cluster",
                position: "toast-top toast-right"
            );
            return;
        } else {
            $result = Reservations::create([
                "user_id" => $data["user_id"],
                "service_id" => $data["service_id"],
                "reservation_datetime" => $data["reserve_datetime"],
                "reserve_type" => $data["reserve_type"],
                "count" => $data["patient_count"],
            ]);
            if ($result) {
                $this->reset();
                $this->success(
                    "Appointment added",
                    position: "toast-top toast-right"
                );
                return;
            } else {
                $this->error(
                    "Appointment added failed",
                    position: "toast-top toast-right"
                );
                return;
            }
        }
    }
};
?>

<div class="w-full space-y-3">
    < x-mary-header size="text-xl md:text-4xl" title="{{ __('Set clinic appointment') }}" separator progress-indicator />
    <x-mary-form wire:submit.prevent="set_appointment" no-separator class="h-full mx-auto w-full md:w-4/5 mt-5">
        <div class="grid md:grid-cols-3 space-y-2 md:space-y-0 md:gap-3">
            <x-mary-datetime label="Set reservation date and time" icon="o-calendar" type="datetime-local" hint="After date then type your desired time" wire:model="reserve_datetime" />
            <x-mary-select label="Services" :options="$this->services" option-value="serv_id" option-label="serv_name" icon="eos.service" placeholder="Select a service you want" wire:model="service_id" />
            <x-mary-select
                label="Appointment Type"
                icon="o-user"
                :options="$this->resrv_type()"
                placeholder="Select appointment type"
                wire:model="reserve_type"
            />
            <x-mary-input type="number" min="1" max="999" label="How many patients" placeholder="Enter patients count" icon="fluentui.people-32" hint="Enter of how many people are with you." wire:model="patient_count" />
        </div>
        <x-slot:actions>
            <x-mary-button label="Submit" class="btn-primary text-white" type="submit" spinner="set_appointment" />
        </x-slot:actions>
    </x-mary-form>
    <div class="">
    </div>
</div>
