<?php

use Mary\Traits\Toast;

use App\Models\DentalClinic;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public object|null $services = null;
    public bool $left_drawer = false;
    public array $items = [];
    public int $count = 0;
    public bool $btn_state = false;

    public function mount(int $clinic_id): void
    {
        // Fetch services with clinic details
        $result = Service::leftJoin(
            "dental_clinic",
            "services.dental_clinic_id",
            "=",
            "dental_clinic.id"
        )
            ->select(
                DB::raw('
                dental_clinic.clinic_name,
                services.service_name,
                services.service_price,
                services.service_description,
                services.updated_at
            ')
            )
            ->where("services.dental_clinic_id", "=", $clinic_id)
            ->get();

        // Assign the fetched results to component properties
        if ($result->isNotEmpty()) {
            $this->services = $result;
        }

        // Initialize items array with one empty entry
        $this->addItem();
    }

    public function headers(): array
    {
        return [
            ["key" => "id", "label" => "#"],
            ["key" => "service_name", "label" => "Service"],
            ["key" => "clinic_name", "label" => "Clinic"],
            ["key" => "service_price", "label" => "Price"],
            ["key" => "service_description", "label" => "Description"],
        ];
    }

    public function addItem(): void
    {
        if ($this->count != 5) {
            $this->items[] = [
                "input" => "",
                "desc" => "",
                "price" => 0.0,
            ];
            $this->count++;
            return;
        }

        $this->warning(
            "Add items max reach of 5",
            position: "toast-top toast-right"
        );
        return;
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="{{ __('Services') }}" separator progress-indicator />
    {{ request('clinic_id') }}
    @if($services)
        <x-mary-table :headers="$headers()" :rows="$services" striped @row-click="alert($event.detail.name)" />
    @else
        <div class="space-y-3 flex flex-col items-center justify-center">
            <h1 class="text-xl font-bold">No service record available</h1>
            <x-mary-button class="btn-primary" label="Add services" @click="$wire.left_drawer = true" />
        </div>
    @endif

    {{-- Left Drawer --}}
    <x-mary-drawer wire:model="left_drawer" class="w-2/3">
        <div class="flex justify-between">
            <h1 class="text-lg font-medium">Add services</h1>
            <x-mary-button icon="majestic.multiply-line" class="btn-circle btn-ghost" @click="$wire.left_drawer = false" />
        </div>
        <x-mary-button icon="ri.add-line" class="btn-accent" label="Add more" wire:click="addItem()" spinner />

        <div class="mt-4 space-y-3 w-full">
            @foreach($items as $index => $item)
                <div class="flex items-center gap-3 w-full">
                    <x-mary-input label="Service name" wire:model="items.{{ $index }}.input" class="rounded-md w-full" />
                    <x-mary-input label="Description" wire:model="items.{{ $index }}.desc" class="rounded-md w-full" />
                    <x-mary-input label="Price" wire:model="items.{{ $index }}.price" class="rounded-md w-full" />
                </div>
            @endforeach
        </div>
    </x-mary-drawer>
</div>
