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

    public function remove(int $id)
    {
        $this->warning(
            "Will delete #$id",
            "It is fake.",
            position: "toast-bottom"
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
            ["key" => "email", "label" => "E-mail", "sortable" => false],
            // ["key" => "created_at", "label" => "Created", "class" => "w-16"],
            // ["key" => "updated_at", "label" => "Updated", "class" => "w-16"],
        ];
    }
};
?>

<div class="w-full p-3">
    <x-mary-table :headers="$this->headers()" :rows="$this->patients()" :sort-by="$this->sortBy()" >
        @scope('actions', $user)
            <x-mary-button icon="o-trash" wire:click="delete({{ $user['detail_id'] }})" wire:confirm="Are you sure? you want to remove this?" spinner class="btn-ghost btn-sm text-red-500" />
        @endscope
    </x-mary-table>
</div>
