<?php

use Mary\Traits\Toast;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public object|null $services = null;
    public bool $left_drawer = false;
    public array $items = [];
    public int $count = 0;
    public bool $btn_state = false;
    public bool $service_modal = false;
    public bool $delete_modal = false;
    public int $clinic_id = 0;
    public int $service_delete_id = 0;
    public int $service_id = 0;
    public int $service_status = 0;

    // Service information fields
    public string $service_name = "";
    public string $service_desc = "";
    public int $service_price = 0;

    public function mount(int $clinic_id): void
    {
        // initializing the clinic id
        $this->clinic_id = $clinic_id;

        $this->fetchServices();
        // Initialize items array with one empty entry
        $this->addItem();
    }

    public function fetchServices(): void
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
                    services.id as service_id,
                    dental_clinic.clinic_name,
                    services.service_name,
                    services.service_price,
                    services.service_description,
                    services.updated_at,
                    services.service_status
                ')
            )
            ->where("services.dental_clinic_id", "=", $this->clinic_id)
            ->get();

        // Assign the fetched results to component properties
        if ($result->isNotEmpty()) {
            $this->services = $result;
        }
    }

    public function headers(): array
    {
        return [
            ["key" => "service_id", "label" => "#"],
            ["key" => "service_name", "label" => "Service"],
            ["key" => "clinic_name", "label" => "Clinic"],
            ["key" => "service_price", "label" => "Price"],
            ["key" => "service_description", "label" => "Description"],
            ["key" => "updated_at", "label" => "Last update"],
            ["key" => "service_status", "label" => "Services Status"],
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

    public function removeItem(int $index): void
    {
        if ($index != 0) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->count--;
            $this->info(
                "One item is removed",
                position: "toast-top toast-center"
            );
        }
        return;
    }

    public function resetItem(): void
    {
        $this->count = 0;
        $this->items = [];
        // $this->info(
        //     "Items input field reset",
        //     position: "toast-top toast-center"
        // );
        $this->addItem();
        return;
    }

    public function checkInputs(): bool
    {
        $flag = true; // Start with the assumption that all inputs are valid

        foreach ($this->items as $item) {
            // Check if any of the fields in the current item are empty or invalid
            if (
                $item["input"] == "" ||
                $item["desc"] == "" ||
                $item["price"] == 0
            ) {
                $flag = false; // Set flag to false if any item fails validation
                break; // Exit the loop early since we found an invalid item
            }
        }

        if (!$flag) {
            $this->warning(
                "Please fill out all fields correctly",
                position: "toast-top toast-center"
            );
        }

        return $flag;
    }

    public function addServices(): void
    {
        if ($this->checkInputs()) {
            foreach ($this->items as $item) {
                // Logic to handle each service item.
                if (
                    $item["input"] != "" &&
                    $item["desc"] != "" &&
                    $item["price"] != 0
                ) {
                    Service::create([
                        "dental_clinic_id" => $this->clinic_id,
                        "service_name" => $item["input"],
                        "service_price" => $item["price"],
                        "service_description" => $item["desc"],
                    ]);
                } else {
                    $flag = true;
                }
            }
        } else {
            return;
        }

        $this->success(
            "Services added successfully",
            position: "toast-top toast-right"
        );
        $this->resetItem();
        $this->fetchServices();
        return;
    }

    public function serviceInfo(array $details): void
    {
        // dd($details);
        $this->service_id = $details["service_id"];
        $this->service_name = $details["service_name"];
        $this->service_desc = $details["service_description"];
        $this->service_price = $details["service_price"];
        $this->service_status = $details["service_status"];
        $this->service_modal = true;
        return;
    }

    public function updateService(): void
    {
        if (
            $this->service_name != "" ||
            $this->service_desc != "" ||
            $this->service_price != ""
        ) {
            $result = Service::find($this->service_id);
            $result->service_name = $this->service_name;
            $result->service_description = $this->service_desc;
            $result->service_price = $this->service_price;
            $result->save();
            $this->service_modal = false;
            $this->success(
                "Service updated successfully",
                position: "toast-top toast-right"
            );
            $this->fetchServices();
            return;
        }

        $this->service_modal = false;
        $this->warning(
            "Do not leave some field/s empty",
            position: "toast-buttom"
        );
        $this->fetchServices();
        return;
    }

    public function closeModal(): void
    {
        $this->service_modal = false;
        $this->fetchServices();
    }

    public function showConfirmation(int $id, int $status): void
    {
        $this->closeModal();
        $this->service_delete_id = $id;
        $this->service_status = $status;
        $this->delete_modal = true;
    }

    public function deleteService(): void
    {
        $result = Service::find($this->service_delete_id);
        $result->service_status = $this->service_status;
        $result->save();
        $this->success(
            "Service updated successfully",
            position: "toast-buttom"
        );
        $this->delete_modal = false;
        $this->fetchServices();
    }
};
?>

<div class="w-full p-3">
    <x-mary-header title="{{ __('Services') }}" separator progress-indicator />
    @if($services)
        <div class="">
            <x-mary-button class="btn-primary text-white" label="Add services" @click="$wire.left_drawer = true" />
        </div>
        <x-mary-table :headers="$this->headers()" :rows="$this->services" striped @row-click="$wire.serviceInfo($event.detail)" >
            @scope('cell_service_status', $service)
                @if($service->service_status == 0)
                    <x-mary-badge value="Available" class="badge-success" />
                @else
                    <x-mary-badge value="Unavailable" class="badge-warning" />
                @endif
            @endscope
        </x-mary-table>
    @else
        <div class="space-y-3 flex flex-col items-center justify-center">
            <h1 class="text-xl font-bold">No service record available</h1>
            <x-mary-button class="btn-primary text-white" label="Add services" @click="$wire.left_drawer = true" />
        </div>
    @endif

    {{-- Left Drawer --}}
    <x-mary-drawer wire:model="left_drawer" class="w-2/3">
        <div class="flex justify-between">
            <h1 class="text-lg font-medium">Add services</h1>
            <x-mary-button icon="majestic.multiply-line" class="btn-circle btn-ghost" @click="$wire.left_drawer = false" />
        </div>
        <x-mary-button icon="ri.add-line" class="btn-accent" label="Add more" wire:click="addItem()" spinner />

        <x-mary-form wire:submit="addServices" no-separator class="mt-4 space-y-3 w-full">
            @foreach($items as $index => $item)
                <div class="flex items-center gap-3 w-full">
                    <x-mary-input label="Service name" wire:model="items.{{ $index }}.input" class="rounded-md w-full" />
                    <x-mary-input label="Description" wire:model="items.{{ $index }}.desc" class="rounded-md w-full" />
                    <x-mary-input label="Price" wire:model="items.{{ $index }}.price" class="rounded-md w-full" />
                    <div class="flex">
                        <x-mary-button icon="ri.delete-bin-line" class="text-error btn-ghost btn-circle" wire:click="removeItem({{ $index }})" spinner />
                    </div>
                </div>
            @endforeach

            <x-slot:actions>
                <x-mary-button label="{{ __('Reset') }}" wire:click="resetItem()" spinner />
                <x-mary-button label="{{ __('Confirm') }}" class="btn-primary" type="submit" spinner="addServices" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>

    <x-mary-modal wire:model="service_modal" title="Details & Update Service" class="backdrop-blur" persistent>
        <x-mary-form wire:submit="updateService" no-separator class="mb-5">
            <x-mary-input label="Service name" wire:model="service_name" class="rounded-md w-full" />
            <x-mary-textarea label="Description" rows="3" wire:model="service_desc" class="rounded-md w-full" />
            <x-mary-input label="Price" wire:model="service_price" class="rounded-md w-full" />

            <x-slot:actions>
                @if($this->service_status == 0)
                    <x-mary-button icon="o-trash" wire:click="showConfirmation({{ $this->service_id }}, 1)" spinner class="text-error btn-ghost btn-circle" />
                @else
                    <x-mary-button icon="fluentui.presence-available-20-o" wire:click="showConfirmation({{ $this->service_id }}, 0)" spinner class="text-success btn-ghost btn-circle" />
                @endif
                <x-mary-button label="{{ __('Close') }}" wire:click="closeModal()" spinner />
                <x-mary-button label="{{ __('Save changes') }}" class="btn-primary" type="submit" spinner="updateService" />
            </x-slot:actions>
        </x-mary-from>
    </x-mary-modal>

    <x-mary-modal wire:model="delete_modal" class="backdrop-blur">
        <div class="mb-5 text-center">
            @if($this->service_status == 1)
                Are you sure you want to remove this Service?
            @else
                Are you sure you want to make this Service available?
            @endif
        </div>
        <div class="grid grid-cols-2 gap-3">
            <x-mary-button label="{{ __('Cancel') }}" class="btn-primary" @click="$wire.delete_modal = false" />
            <x-mary-button label="{{ __('Confirm') }}" class="btn-ghost" wire:click="deleteService()" spinner />
        </div>
    </x-mary-modal>
</div>
