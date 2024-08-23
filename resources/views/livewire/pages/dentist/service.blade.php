<?php

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    //
    public object|null $services = null;

    public function mount(): void
    {
        $result = Service::leftjoin(
            "dental_clinic",
            "services.dental_clinic_id",
            "=",
            "dental_clinic.id"
        )
            ->select(
                DB::raw('
            dental_clinic.id AS clinic_id,
            dental_clinic.clinic_name,
            services.service_name,
            services.service_price,
            services.description,
            services.updated_at,
        ')
            )
            ->get();

        if ($result->isNotEmpty()) {
            $this->services = $result;
        }
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
};
?>

<div class="w-full p-3">
    <x-mary-header title="{{ __('Services') }}" separator progress-indicator />

    @if($this->isNotEmpty())
        <x-mary-table :headers="$this->headers()" :rows="$services" striped @row-click="alert($event.detail.name)" />
    @else
        <div class="text-center font-bold text-xl">
            No service record available
        </div>
    @endif
</div>
