<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout("layouts.guest")] #[Title("Login")] class extends Component {
    public ?int $temp_role = null;
    public string $first_name = "";
    public string $last_name = "";
    public string $contact_no = "";
    public string $gender = "";
    public string $address = "";
    public string $role = "";
    public ?string $dental_clinic_name = null;
    public string $email = "";
    public string $password = "";
    public string $password_confirmation = "";

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            "first_name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            "contact_no" => ["required", "string", "max:13"],
            "gender" => ["required", "string", "max:255"],
            "address" => ["required", "string", "max:255"],
            "role" => ["required", "string", "max:255"],
            "dental_clinic_name" => ["nullable", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            "password" => [
                "required",
                "string",
                "confirmed",
                Rules\Password::defaults(),
            ],
        ]);

        $validated["password"] = Hash::make($validated["password"]);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route("dashboard", absolute: false), navigate: true);
    }

    public function genders(): array
    {
        return [
            ["id" => 1, "name" => "Male"],
            ["id" => 2, "name" => "Female"],
            ["id" => 3, "name" => "Other"],
        ];
    }

    public function roles(): array
    {
        return [
            ["id" => 1, "label_name" => "patient"],
            ["id" => 2, "label_name" => "dentist"],
        ];
    }
};
?>

<div class="w-full p-2 sm:p-0 sm:w-3/5 md:w-3/4 lg:w-4/5 mx-auto mt-20">
    <x-mary-form wire:submit="register" class="space-y-3">
        <!-- Full name -->
        <span class="mb-2 font-bold text-lg">Register your account.</span>
        <div class="space-y-2 md:grid md:grid-cols-2 gap-2 items-center">
            <x-mary-input  class="rounded-lg" label="First name" wire:model="first_name" required type="text" name="first_name" autofocus autocomplete="first_name" inline />
            <x-mary-input  class="rounded-lg" label="Last name" wire:model="last_name" required type="text" name="last_name" autofocus autocomplete="last_name" inline />
        </div>

        <!-- Contact address and number -->
        <div class="space-y-2 md:grid md:grid-cols-2 gap-2 items-center">
            <x-mary-input  class="rounded-lg" label="Complete address" wire:model="address" required type="text" name="address" autofocus autocomplete="address" inline />
            <x-mary-input  class="rounded-lg" label="Contact number" wire:model="contact_no" required type="text" name="contact_no" autofocus autocomplete="contact_no" inline />
            <x-mary-select class="rounded-lg" class="rounded-lg" label="Gender" :options="$this->genders()" required wire:model="gender" inline />
        </div>

        <!-- Login information -->
        <div class="space-y-2">
            <x-mary-input  class="rounded-lg" label="E-mail" wire:model="email" icon="o-envelope" inline />
            <x-mary-input  class="rounded-lg" label="Password" wire:model="password" type="password" icon="o-key" inline />
            <x-mary-input  class="rounded-lg" label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key" inline />
        </div>

        <!-- Account identity -->
        <div class="space-y-2 md:grid md:grid-cols-2 gap-2 items-center">
            <x-mary-radio
                label="Select one"
                :options="$this->roles()"
                option-value="value_name"
                option-label="label_name"
                wire:model="temp_role"
                class="bg-red-50 w-full" />
            @if($temp_role == 2)
                <x-mary-input
                    class="rounded-lg"
                    label="Dental clinic name" wire:model="contact_no" required type="text" name="contact_no" autofocus autocomplete="contact_no" inline />
            @else
                <span>Test</span>
            @endif
        </div>

        <x-slot:actions>
            <x-mary-button label="{{ __('Already registered?') }}" class="btn-ghost" link="{{ route('login') }}" class="rounded-lg" />
            <x-mary-button label="{{ __('Register') }}" type="submit" class="btn-primary rounded-md text-white" spinner="register" />
        </x-slot:actions>
    </x-mary-form>
</div>
