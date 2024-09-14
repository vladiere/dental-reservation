<?php

use App\Models\DentistSchedule;
use Livewire\Attributes\Rule;
use Mary\Traits\Toast;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    #[Rule("required|integer")]
    public int $doc_id;
    #[Rule("required|string|max:255")]
    public string $sched_days = "";
    #[Rule("required|string|max:255")]
    public string $time_from = "";
    #[Rule("required|string|max:255")]
    public string $time_to = "";

    public function doctors(): Collection
    {
        return User::query()
            ->leftjoin("details", "users.details_id", "=", "details.id")
            ->select(
                DB::raw("
            users.id,
            concat(details.last_name, ', ', details.first_name, ' ', details.middle_name) as full_name,
            details.contact_no,
            details.gender
        ")
            )
            ->where("users.user_role", "=", 2)
            ->get();
    }

    public function add_schedule(): void
    {
        $data = $this->validate();
        if ($data["sched_days"] == "weekdays") {
            $data["sched_days"] = "mon, tue, wed, thu, fri";
        }

        $result = DentistSchedule::create([
            "user_id" => $data["doc_id"],
            "sched_days" => $data["sched_days"],
            "time_from" => $data["time_from"],
            "time_to" => $data["time_to"],
            "sched_status" => 0,
        ]);

        if ($result) {
            $this->reset();
            $this->success(
                "Schedule added successfully.",
                position: "toast-top top-right"
            );
        } else {
            $this->warning(
                "Add schedule failed.",
                position: "toast-top top-right"
            );
        }
        return;
    }
};
?>

<div class="w-full p-3">
    < x-mary-header size="text-xl md:text-4xl" title="{{ __('Assign Doctor Schedule') }}" separator progress-indicator />

    <x-mary-form wire:submit="add_schedule" >
        <!-- Full name -->
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 gap-2 ">
            <x-mary-select
                label="Doctors"
                :options="$this->doctors()"
                {{-- option-value="user_id" --}}
                option-label="full_name"
                placeholder="Select a doctor"
                placeholder-value="0" {{-- Set a value for placeholder. Default is `null` --}}
                wire:model="doc_id" />
            <x-mary-input  class="rounded-lg" label="Schedule days" wire:model="sched_days" required name="sched_date" icon="bi.calendar-date" hint="Separate it with comma (enter weekdays if you want mon to fri)" />
        </div>

        <!-- Contact address and number -->
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 gap-2 ">
            <x-mary-datetime label="Time from" wire:model="time_from" icon="ri.timer-2-line" type="time" />
            <x-mary-datetime label="Time to" wire:model="time_to" icon="iconpark.time-o" type="time" />
        </div>

        <x-slot:actions>
            <x-mary-button label="{{ __('Add schedule') }}" type="submit" class="btn-primary rounded-md text-white" spinner="add_schedule" />
        </x-slot:actions>
    </x-mary-form>
</div>
