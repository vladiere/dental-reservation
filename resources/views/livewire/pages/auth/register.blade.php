<?php

use App\Models\Details;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout("layouts.guest")] #[Title("Login")] class extends Component {
    #[Rule("required|string|max:255")]
    public string $first_name = "";
    #[Rule("required|string|max:255")]
    public string $last_name = "";
    #[Rule("required|string|max:11")]
    public string $contact_no = "";
    #[Rule("required|string|max:255")]
    public string $gender = "";
    #[Rule("required|string|max:255")]
    public string $address = "";
    #[Rule("required|string|max:255")]
    public string $role = "";
    #[Rule("nullable|string|max:255")]
    public ?string $dental_clinic_name = null;
    #[Rule("required|string|max:255")]
    public string $email = "";
    #[Rule("required|string|max:255")]
    public string $password = "";
    #[Rule("required|string|max:255")]
    public string $password_confirmation = "";

    public function mount()
    {
        if (Auth::user()) {
            return redirect()->route("dashboard");
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $data = $this->validate();
        $data["password"] = Hash::make($data["password"]);

        $data["gender"] = $this->check_gender($data["gender"]);
        $data["role"] = $this->check_roles($data["role"]);
        $user_detail = Details::create([
            "first_name" => $data["first_name"],
            "last_name" => $data["last_name"],
            "contact_no" => $data["contact_no"],
            "gender" => $data["gender"],
            "address" => $data["address"],
            "dental_clinic_name" => $data["dental_clinic_name"],
        ]);
        $user = User::create([
            "details_id" => $user_detail->id,
            "role" => $data["role"],
            "email" => $data["email"],
            "password" => $data["password"],
        ]);

        Auth::login($user);

        $this->redirect(route("dashboard", absolute: false), navigate: true);
    }

    public function genders(): array
    {
        return [
            ["id" => "male", "name" => "Male"],
            ["id" => "female", "name" => "Female"],
            ["id" => "other", "name" => "Other"],
        ];
    }

    public function roles(): array
    {
        return [
            ["id" => 1, "name" => "Patient"],
            ["id" => 2, "name" => "Dentist"],
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

    public function check_roles(string $role): string
    {
        if ($role == "1") {
            return "patient";
        } else {
            return "dentist";
        }
    }
};
?>

<div class="w-full p-2 sm:p-0 sm:w-3/5 md:w-3/4 lg:w-4/5 mx-auto mt-2">
    <x-mary-form wire:submit="register" class="space-y-3">
        <!-- Full name -->
        <span class="mb-2 font-bold text-lg">Register your account.</span>
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 gap-2 ">
            <x-mary-input  class="rounded-lg" label="First name" wire:model="first_name" required type="text" name="first_name" autofocus autocomplete="first_name" inline />
            <x-mary-input  class="rounded-lg" label="Last name" wire:model="last_name" required type="text" name="last_name" autofocus autocomplete="last_name" inline />
        </div>

        <!-- Contact address and number -->
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-3 gap-2 ">
            <x-mary-input  class="rounded-lg" label="Complete address" wire:model="address" required type="text" name="address" autofocus autocomplete="address" inline />
            <x-mary-input  class="rounded-lg" label="Contact number" max="13" wire:model="contact_no" required type="text" name="contact_no" autofocus autocomplete="contact_no" inline />
            <x-mary-select class="rounded-lg" label="Gender" :options="$this->genders()" required wire:model="gender" inline />
        </div>

        <!-- Login information -->
        <div class="space-y-2">
            <x-mary-input  class="rounded-lg" label="E-mail" wire:model="email" icon="o-envelope" inline />
            <x-mary-input  class="rounded-lg" label="Password" wire:model="password" type="password" icon="o-key" inline />
            <x-mary-input  class="rounded-lg" label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key" inline />
        </div>

        <!-- Account identity -->
        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 gap-2 ">
           <x-mary-radio label="Register as?" :options="$this->roles()" wire:model="role" class="w-full rounded-lg text-dark" />
        </div>

        <x-slot:actions>
            <x-mary-button label="{{ __('Already registered?') }}" class="btn-ghost" link="{{ route('login') }}" class="rounded-lg" />
            <x-mary-button label="{{ __('Register') }}" type="submit" class="btn-primary rounded-md text-white" spinner="register" />
        </x-slot:actions>
    </x-mary-form>
</div>
