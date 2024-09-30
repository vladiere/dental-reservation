<?php

use App\Models\Reservations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Illuminate\Support\Carbon;

new class extends Component {
    protected object $bookings;
    public array $soryBy = [
        "column" => "reservations.id",
        "direction" => "asc",
    ];

    public function mount(string $book_type): void
    {
        $this->fetch_bookings($book_type);
        return;
    }

    function fetch_bookings(string $book_type): void
    {
        $this->bookings = DB::table("reservations", "r")
            ->leftjoin("users as u", "r.user_id", "=", "u.id")
            ->leftjoin("services as s", "r.service_id", "=", "s.id")
            ->leftjoin(
                "dental_clinic as dc",
                "s.dental_clinic_id",
                "=",
                "dc.id"
            )
            ->select(
                DB::raw("
                        r.id as resrv_id,
                        r.reservation_datetime as date_time,
                        r.count as num_count,
                        r.created_at,
                        dc.clinic_name,
                        dc.clinic_address,
                        s.service_name,
                        s.service_price,
                        r.reservation_status as resrv_status
                ")
            )
            ->where("r.reserve_type", "=", $book_type)
            ->get();
        // $this->bookings = Reservations::query()
        //     ->select(
        //         DB::raw("
        //             reservations.id as resrv_id,
        //             reservations.reservation_datetime as date_time,
        //             reservations.count as num_count,
        //             reservations.created_at,
        //             dental_clinic.clinic_name,
        //             dental_clinic.clinic_address,
        //             services.service_name,
        //             services.service_price,
        //             reservations.reservation_status as resrv_status
        //     ")
        //     )
        //     ->leftjoin("users", "reservations.user_id", "=", "users.id")
        //     ->leftjoin("services", "reservations.service_id", "=", "service_id")
        //     ->leftjoin(
        //         "dental_clinic",
        //         "services.dental_clinic_id",
        //         "=",
        //         "dental_clinic.id"
        //     )
        //     ->where("reservations.reserve_type", "=", $book_type)
        //     ->where("users.id", "=", Auth::user()->id)
        //     ->orderBy(...array_values($this->soryBy))
        //     ->get();

        return;
    }

    public function headers(): array
    {
        return [
            ["key" => "resrv_id", "label" => "#", "class" => "w-10"],
            [
                "key" => "date_time",
                "label" => "Reservation Datetime",
            ],
            [
                "key" => "num_count",
                "label" => "Patient count",
                "class" => "w-10",
            ],
            ["key" => "created_at", "label" => "Date set"],
            [
                "key" => "clinic_name",
                "label" => "Clinic name",
                "class" => "w-32",
                "sortable" => false,
            ],
            [
                "key" => "clinic_address",
                "label" => "Clinic address",
                "sortable" => false,
            ],
            [
                "key" => "service_name",
                "label" => "Service",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "service_price",
                "label" => "Service Price",
                "class" => "w-24",
                "sortable" => false,
            ],
            [
                "key" => "resrv_status",
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

    // public function resrv_status(): array
    //     {
    //         // 0    --- Pending
    //         // 1    --- Accept
    //         // 2    --- Denied/Reject
    //         // 3    --- Complete
    //         // 4    --- Error
    //         return [
    //             ["id" => 0, "name" => "Pending"],
    //             ["id" => 1, "name" => "Accept"],
    //             ["id" => 2, "name" => "Reject"],
    //             ["id" => 3, "name" => "Complete"],
    //             // [ "id" => 4, "name" => 'Error'],
    //         ];
    //     }
};
?>

<div class="w-full p-3">
    < x-mary-header size="text-xl md:text-4xl" title="{{ __('Booking') }}" separator progress-indicator />

    <x-mary-table :headers="$this->headers()" :rows="$this->bookings" >
        @scope('cell_resrv_status', $user)
            @if($user->resrv_status == 0)
                <x-mary-badge value="Pending" class="badge-warning" />
            @elseif ($user->resrv_status == 1)
                <x-mary-badge value="Accepted" class="badge-gray-500 text-white" />
            @elseif($user->resrv_status == 2)
                <x-mary-badge value="Rejected" class="badge-error" />
            @elseif($user->resrv_status == 3)
                <x-mary-badge value="Completed" class="badge-success" />
            @else
                <x-mary-badge value="Error" class="badge-error" />
            @endif
        @endscope
        @scope('cell_service_price', $user)
            {{ $user->service_price * $user->num_count }}
        @endscope
        @scope('cell_date_time', $user)
            {{ Carbon::parse($user->date_time)->format("M d, Y h:i A") }}
        @endscope
    </x-mary-table>

</div>
