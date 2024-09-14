<?php

use App\Models\Reservations;
use Illuminate\Support\Facades\DB;
use Mary\Traits\Toast;

use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public object|null $reservations = null;

    public array $soryBy = [
        "column" => "reservations.id",
        "direction" => "asc",
    ];

    public function mount(): void
    {
        $this->fetch_reservation();
    }

    public function fetch_reservation(): void
    {
        $this->reservations = Reservations::query()
            ->leftjoin("users", "reservations.user_id", "=", "users.id")
            ->leftjoin("details", "users.details_id", "=", "details.id")
            ->leftjoin(
                "services",
                "reservations.service_id",
                "=",
                "services.id"
            )
            ->select(
                DB::raw("
            services.service_name,
            services.service_price,
            reservations.id as resrv_id,
            reservations.reservation_datetime,
            reservations.reserve_type,
            reservations.count as patient_count,
            reservations.reservation_status,
            concat(details.first_name, ' ', SUBSTRING(details.middle_name, 1, 1), '. ', details.last_name) as full_name
        ")
            )
            ->orderBy(...array_values($this->soryBy))
            ->get();

        dd($this->reservations);
    }

    public function headers(): array
    {
        return [
            ["key" => "resrv_id", "label" => "#", "class" => "w-10"],
            ["key" => "full_name", "label" => "Full Name", "class" => "w-40"],
            [
                "key" => "service_name",
                "label" => "Service",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "service_price",
                "label" => "Service amount",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "resrv_datetime",
                "label" => "Reservation time",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "reserve_type",
                "label" => "Reservation Type",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "patient_count",
                "label" => "Patient count",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "reservation_status",
                "label" => "Status",
                "class" => "w-24",
                "sortable" => false,
            ],
        ];
    }

    public function sortBy(): array
    {
        return [
            "column" => "reservations.id",
            "direction" => "asc",
        ];
    }

    public function show_detail(array $details): void
    {
        dd($details);
        return;
    }
};
?>

<div class="w-full p-3">
    <x-mary-header size="text-xl md:text-4xl" title="{{ __('Appointments') }}" separator progress-indicator />
    <x-mary-table :headers="$this->headers()" :rows="$this->reservations" :sort-by="$this->sortBy()" @row-click="$wire.show_detail($event.detail)" >
</div>
