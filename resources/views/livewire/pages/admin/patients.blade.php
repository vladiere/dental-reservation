<?php

use App\Models\Details;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public array $soryBy = [
        "column" => "details.id",
        "direction" => "asc",
    ];
    public bool $detail_modal = false;
    public string|null $first_name = "";
    public string|null $last_name = "";
    public string|null $middle_name = "";
    public string|null $contact_no = "";
    public string|null $gender = "";
    public string|null $address = "";
    public string|null $email = "";
    public string|null $dental_clinic = "";

    public function remove(int $id)
    {
        $this->warning(
            "Will delete #$id",
            "It is fake.",
            position: "toast-top toast-right"
        );
    }

    public function patients(): Collection
    {
        return Details::query()
            ->leftJoin("users", "details.id", "=", "users.details_id")
            ->select(
                DB::raw('
                details.id as detail_id,
                details.first_name,
                details.middle_name,
                details.last_name,
                details.gender,
                details.contact_no,
                details.address,
                users.email,
                users.created_at,
                users.updated_at
            ')
            )
            ->where("users.role", "=", "patient")
            ->where("details.acct_status", "=", 0)
            ->orderBy(...array_values($this->soryBy))
            ->get();
    }

    public function sortBy(): array
    {
        return [
            "column" => "details.id",
            "direction" => "asc",
        ];
    }

    public function headers(): array
    {
        return [
            ["key" => "detail_id", "label" => "#", "class" => "w-10"],
            ["key" => "first_name", "label" => "First Name", "class" => "w-16"],
            [
                "key" => "middle_name",
                "label" => "Middle Name",
                "class" => "w-16",
            ],
            ["key" => "last_name", "label" => "Last Name", "class" => "w-16"],
            ["key" => "gender", "label" => "Gender", "class" => "w-8"],
            [
                "key" => "address",
                "label" => "Complete Address",
                "class" => "w-92",
            ],
            [
                "key" => "contact_no",
                "label" => "Contact No.",
                "class" => "w-16",
            ],
            [
                "key" => "email",
                "label" => "E-mail",
                "class" => "w-24",
                "sortable" => false,
            ],
            ["key" => "created_at", "label" => "Created", "class" => "w-16"],
            ["key" => "updated_at", "label" => "Updated", "class" => "w-16"],
        ];
    }

    public function show_detail(array $details)
    {
        $this->first_name = $details["first_name"];
        $this->last_name = $details["last_name"];
        $this->middle_name = $details["middle_name"] ?? "N/A";
        $this->contact_no = $details["contact_no"] ?? "N/A";
        $this->address = $details["address"] ?? "N/A";
        $this->gender = $details["gender"] ?? "N/A";
        $this->email = $details["email"] ?? "N/A";
        $this->dental_clinic = $details["dental_clinic_name"] ?? "N/A";
        $this->detail_modal = true;
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="List all Patients" separator progress-indicator />
    <x-mary-table :headers="$this->headers()" :rows="$this->patients()" :sort-by="$this->sortBy()" />
</div>
