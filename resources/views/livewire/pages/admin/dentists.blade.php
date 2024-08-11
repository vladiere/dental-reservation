
<?php
use App\Models\Details;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $detail_modal = false;
    public array $soryBy = [
        "column" => "details.id",
        "direction" => "asc",
    ];
    public string|null $first_name = "";
    public string|null $last_name = "";
    public string|null $middle_name = "";
    public string|null $contact_no = "";
    public string|null $gender = "";
    public string|null $address = "";
    public string|null $email = "";
    public string|null $dental_clinic = "";
    public string|null $created = "";
    public string|null $updated = "";

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
            ->where("users.role", "=", "dentist")
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
                "key" => "contact_no",
                "label" => "Contact No.",
                "class" => "w-16",
            ],
            ["key" => "email", "label" => "E-mail", "sortable" => false],
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
        $this->created = $details["created_at"];
        $this->updated = $details["updated_at"];
        $this->detail_modal = true;
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="List all Dentist" separator progress-indicator />
    <x-mary-table :headers="$this->headers()" :rows="$this->patients()" :sort-by="$this->sortBy()" @row-click="$wire.show_detail($event.detail)" />

    <x-mary-modal wire:model="detail_modal" class="backdrop-blur">
        <div class="space-y-2 mb-2">
            <!-- Full name -->
            <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-3 gap-2 ">
                <x-mary-input disabled  class="rounded-lg" label="First name" wire:model="first_name" />
                <x-mary-input disabled  class="rounded-lg" label="Last name" wire:model="last_name" />
                <x-mary-input disabled  class="rounded-lg" label="Middle name" wire:model="middle_name" />
            </div>

            <!-- Contact address and number -->
            <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 gap-2 ">
                <x-mary-input disabled  class="rounded-lg" label="Contact number" wire:model="contact_no" />
                <x-mary-input disabled  class="rounded-lg" label="Gender" wire:model="gender" />
            </div>

            <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 gap-2 ">
                <x-mary-input disabled  class="rounded-lg" label="Created" wire:model="created" />
                <x-mary-input disabled  class="rounded-lg" label="Updated" wire:model="updated" />
            </div>

            <x-mary-input disabled  class="rounded-lg" label="E-mail" wire:model="email" />
            <x-mary-input disabled  class="rounded-lg" label="Complete address" wire:model="address"  />
            <x-mary-input disabled  class="rounded-lg" label="Dental Clinic" wire:model="dental_clinic" />

        </div>
        <x-mary-button label="Cancel" @click="$wire.detail_modal = false" />
    </x-mary-modal>
</div>
