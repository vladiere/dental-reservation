<?php

use App\Models\DentistSchedule;
use Mary\Traits\Toast;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use App\Support\TimeRange;

new class extends Component {
    use Toast;

    public bool $sched_status_modal = false;
    public object|null $doc_sched = null;
    public int $sched_status = 0;
    public int $sched_id = 0;

    public array $soryBy = [
        "column" => "users.id",
        "direction" => "asc",
    ];

    public function mount(): void
    {
        $this->doc_schedules();
    }

    public function doc_schedules(): void
    {
        $this->doc_sched = User::query()
            ->leftjoin("details", "users.details_id", "=", "details.id")
            ->leftjoin(
                "dentist_schedules",
                "users.id",
                "=",
                "dentist_schedules.user_id"
            )
            ->select(
                DB::raw("
            users.id as user_id,
            dentist_schedules.id as sched_id,
            concat(details.last_name, ', ', details.first_name, ' ', details.middle_name) as full_name,
            dentist_schedules.sched_days,
            dentist_schedules.time_from,
            dentist_schedules.time_to,
            dentist_schedules.sched_status,
            dentist_schedules.created_at
        ")
            )
            ->where("users.user_role", "=", 2)
            ->orderBy(...array_values($this->soryBy))
            ->get();
    }

    public function headers(): array
    {
        return [
            ["key" => "user_id", "label" => "#", "class" => "w-10"],
            ["key" => "full_name", "label" => "Full Name", "class" => "w-40"],
            [
                "key" => "sched_days",
                "label" => "Scheduled Days",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "sched_time",
                "label" => "Schedule Time",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "sched_status",
                "label" => "Doctor Status",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "created_at",
                "label" => "Created at",
                "class" => "w-24",
                "sortable" => false,
            ],
        ];
    }

    public function sortBy(): array
    {
        return [
            "column" => "users.id",
            "direction" => "asc",
        ];
    }

    public function update_sched_status(): void
    {
        $result = DentistSchedule::find($this->sched_id);
        $result->sched_status = $this->sched_status;
        $result->save();
        $this->doc_schedules();
        $this->success(
            "Schedule status updated",
            position: "toast-top toast-right"
        );
        $this->sched_status_modal = false;
    }

    public function show_detail(array $details)
    {
        $this->sched_status_modal = true;
        $this->sched_id = $details["sched_id"];
    }

    public function confirm_update(): void
    {
        $this->update_sched_status();
    }

    public function sched_status(): array
    {
        return [
            ["id" => 0, "name" => "Available"],
            ["id" => 1, "name" => "Unavailable"],
            ["id" => 2, "name" => "Out of office"],
            ["id" => 3, "name" => "Busy"],
        ];
    }
};
?>

<div class="w-full p-3">
    < x-mary-header size="text-xl md:text-4xl" title="{{ __('Doctors Schedules') }}" separator progress-indicator />
    <div class="flex items-center">
        <x-mary-button icon="o-plus" class="btn-circle btn-sm btn-outline" tooltip-right="Add doctor schedules" link="{{ route('new_schedule') }}" />
    </div>
    <x-mary-table :headers="$this->headers()" :rows="$this->doc_sched" :sort-by="$this->sortBy()" @row-click="$wire.show_detail($event.detail)" >
        @scope('cell_sched_time', $user)
            {{ TimeRange::stringify($user->time_from, $user->time_to) }}
        @endscope
        @scope('cell_sched_status', $user)
            @if($user->sched_status == 0)
                <x-mary-badge value="Available" class="badge-success" />
            @elseif ($user->sched_status == 1)
                <x-mary-badge value="Unavailable" class="badge-gray-500 text-white" />
            @elseif($user->sched_status == 2)
                <x-mary-badge value="Out of office" class="badge-warning" />
            @else
                <x-mary-badge value="Busy" class="badge-error" />
            @endif
        @endscope
    </x-mary-table>

    <x-mary-modal wire:model="sched_status_modal" title="Update Schedule status" no-separator persistent >
        <x-mary-select label="Schedule Status" icon="fluentui.status-24-o" :options="$this->sched_status()" wire:model="sched_status" />
        <x-slot:actions>
            <x-mary-button label="Cancel" @click="$wire.sched_status_modal = false" />
            <x-mary-button label="Confirm" class="btn-primary" @click="$wire.confirm_update()" spinner="confirm_update" />
        </x-slot:actions>
    </x-mary-modal>
</div>
