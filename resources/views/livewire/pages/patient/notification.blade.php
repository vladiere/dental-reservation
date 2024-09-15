<?php

use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

use App\Models\WebNotification;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public object $notifications;

    public function mount(): void
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications(): void
    {
        $this->notifications = WebNotification::where(
            "user_id",
            "=",
            Auth::user()->id
        )->get();
    }

    public function headers(): array
    {
        return [
            ["key" => "id", "label" => "#"],
            ["key" => "web_message", "label" => "Message"],
            [
                "key" => "web_date_time",
                "label" => "Date time",
                "sortable" => false,
            ],
            ["key" => "notif_status", "label" => "Status"],
        ];
    }

    public function readNotif($notif_id): void
    {
        if ($notif_id != 0) {
            $result = WebNotification::find($notif_id);
            $result->notif_status = 1;
            $result->save();

            $this->fetchNotifications();
            return;
        }

        WebNotification::where("id", ">", 0)->update(["notif_status" => 1]);

        $this->fetchNotifications();
        return;
    }
};
?>

<div class="w-full p-3">
    <x-mary-header size="text-xl md:text-4xl" title="{{ __('Notifications') }}" separator progress-indicator >
        <x-slot:actions>
            <x-mary-button label="Read all" class="btn-ghost text-success" @click="$wire.readNotif(0)" icon="bi.check-all" spinner="readNotif" />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-table :headers="$this->headers()" :rows="$this->notifications" striped @row-click="$wire.readNotif($event.detail['id'])" show-empty-text empty-text="No Available data." >
        @scope('cell_notif_status', $notif)
            @if($notif->notif_status == 0)
                <x-mary-badge value="Unread" class="badge-warning" />
            @else
                <x-mary-badge value="Read" class="" />
            @endif
        @endscope
    </x-mary-table>
</div>
