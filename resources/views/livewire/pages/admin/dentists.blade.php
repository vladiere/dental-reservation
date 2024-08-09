
<?php
use App\Models\Details;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new class extends Component {
    public array $soryBy = [
        "column" => "details.id",
        "direction" => "asc",
    ];

    public function patients(): Collection
    {
        return Details::query()
            ->leftJoin("users", "details.id", "=", "users.details_id")
            ->where("users.role", "=", "dentist")
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
            ["key" => "id", "label" => "#", "class" => "w-10"],
            ["key" => "first_name", "label" => "First Name", "class" => "w-72"],
            [
                "key" => "middle_name",
                "label" => "Middle Name",
                "class" => "w-72",
            ],
            ["key" => "last_name", "label" => "Last Name", "class" => "w-72"],
            ["key" => "gender", "label" => "Gender", "class" => "w-8"],
            [
                "key" => "address",
                "label" => "Complete Address",
                "class" => "w-80",
            ],
            [
                "key" => "contact_no",
                "label" => "Contact No.",
                "class" => "w-16",
            ],
            ["key" => "email", "label" => "E-mail", "sortable" => false],
            [
                "key" => "dental_clinic_name",
                "label" => "Dental Clinic",
                "class" => "w-72",
            ],
        ];
    }
};
?>

<div class="w-full p-3">
    <x-mary-table :headers="$this->headers()" :rows="$this->patients()" :sort-by="$this->sortBy()" />
</div>
