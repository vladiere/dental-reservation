<?php

use App\Models\Details;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Rule("required|string|max:255")]
    public string $first_name = "";
    #[Rule("required|string|max:255")]
    public string $last_name = "";
    #[Rule("required|string|max:255")]
    public string $middle_name = "";
    #[Rule("required|string|max:11")]
    public string $contact_no = "";
    #[Rule("required|string|max:255")]
    public string $gender = "";
    #[Rule("required|string|max:255")]
    public string $address = "";
    #[Rule("required|string|max:255")]
    public string $email = "";
    #[Rule("required|string|max:255")]
    public string $password = "";
    #[Rule("required|string|max:255")]
    public string $password_confirmation = "";

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $data = $this->validate();
        $data["password"] = Hash::make($data["password"]);

        $user_detail = Details::create([
            "first_name" => $data["first_name"],
            "middle_name" => $data["middle_name"],
            "last_name" => $data["last_name"],
            "contact_no" => $data["contact_no"],
            "gender" => $data["gender"],
            "address" => $data["address"],
        ]);

        $user = User::create([
            "details_id" => $user_detail->id,
            "role" => "subadmin",
            "email" => $data["email"],
            "password" => $data["password"],
        ]);

        if ($user) {
            $this->reset();
            $this->success(
                "Admin added successfully.",
                position: "toast-top top-right"
            );
        } else {
            $this->warning(
                "Add admin failed.",
                position: "toast-top top-right"
            );
        }
    }

    public function genders(): array
    {
        return [
            ["id" => "male", "name" => "Male"],
            ["id" => "female", "name" => "Female"],
            ["id" => "other", "name" => "Other"],
        ];
    }

    public function check_gender(string $gender): string
    {
        if ($gender == "1") {
            return "male";
        } elseif ($gender == "2") {
            return "female";
        } else {
            return "other";
        }
    }

    public function clear(): void
    {
        $this->reset();
        $this->success("Inputs cleared.", position: "toast-bottom");
    }
};
?>

<div class="w-full p-2 sm:p-0 sm:w-3/5 md:w-3/4 lg:w-4/5 mx-auto mt-2">
    <!-- HEADER -->
    <x-mary-header title="Adding new Admin" separator progress-indicator />

    <x-mary-form wire:submit="register" class="">
        <!-- Full name -->
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-3 gap-2 ">
            <x-mary-input  class="rounded-lg" label="First name" wire:model="first_name" required type="text" name="first_name" autofocus autocomplete="first_name" inline />
            <x-mary-input  class="rounded-lg" label="Last name" wire:model="last_name" required type="text" name="last_name" autofocus autocomplete="last_name" inline />
            <x-mary-input  class="rounded-lg" label="Middle name" wire:model="middle_name" required type="text" name="middle_name" autofocus autocomplete="middle_name" inline />
        </div>

        <!-- Contact address and number -->
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-3 gap-2 ">
            <x-mary-input  class="rounded-lg" label="Complete address" wire:model="address" required type="text" name="address" autofocus autocomplete="address" inline />
            <x-mary-input  class="rounded-lg" label="Contact number" max="13" wire:model="contact_no" required type="text" name="contact_no" autofocus autocomplete="contact_no" inline />
            <x-mary-select class="rounded-lg" label="Gender" placeholder="Select your gender" :options="$this->genders()" required wire:model="gender" inline />
        </div>

        <!-- Login information -->
        <div class="space-y-2">
            <x-mary-input  class="rounded-lg" label="E-mail" wire:model="email" icon="o-envelope" inline />
            <x-mary-input  class="rounded-lg" label="Password" wire:model="password" type="password" icon="o-key" inline />
            <x-mary-input  class="rounded-lg" label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key" inline />
        </div>

        <x-slot:actions>
            <x-mary-button label="{{ __('Add admin') }}" type="submit" class="btn-primary rounded-md text-white" spinner="register" />
        </x-slot:actions>
    </x-mary-form>
</div>
