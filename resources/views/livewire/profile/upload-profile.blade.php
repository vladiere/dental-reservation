<?php

use Livewire\Attributes\Rule;
use Mary\Traits\Toast;

use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    #[Rule("required|meme:png,jpg,avif,jpeg|max:1024")]
    public $file;

    public function upload(): void
    {
        return;
    }
};
?>

<section class="w-full">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Upload profile image') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Upload your image for the patients to know you.") }}
        </p>
    </header>

    <x-mary-form>
        <x-file wire:model="file" accept="image/png" crop-after-change>
            <img src="{{ $user->avatar ?? '/empty-user.jpg' }}" class="h-40 rounded-lg" />
        </x-file>
    </x-mary-form>
</section>
