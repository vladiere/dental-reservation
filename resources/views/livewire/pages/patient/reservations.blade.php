<?php

use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public int $clinic_id = 0;
    public object|null $schedules = null;
    public object|null $servies = null;

    public function mount(int $clinic_id): void
    {
        $this->clinic_id = $clinic_id;

        $this->fetchClinic();
    }

    public function fetchClinic(): void
    {
        $result = Service::where(
            "dental_clinic_id",
            "=",
            $this->clinic_id
        )->get();
        $result1 = Schedule::where(
            "dental_clinic_id",
            "=",
            $this->clinic_id
        )->get();

        $this->services = $result;
        $this->schedules = $result1;
    }
};
?>

<div class="w-full space-y-3">
    //
</div>
