<?php

use App\Models\ProfileImg;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use Toast, WithFileUploads;

    #[Validate("required|file|mimes:png,jpg,avif,jpeg,webp")]
    public $file;

    public function uploadImg(): void
    {
        $this->validate();
        $path = $this->file->store("file-img", "public");

        $result = ProfileImg::updateOrInsert([
            "user_id" => Auth::user()->id,
            "img_path" => $path,
        ]);

        if ($result) {
            $this->success(
                "Profile upload success",
                position: "toast-top toast-right"
            );
            $this->reset("file");
            return;
        }

        $this->error(
            "Something went wrong when uploading image",
            position: "toast-buttom"
        );
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

    <x-mary-form wire:submit="uploadImg" no-separator class="mt-6 space-y-3 w-full" enctype="multipart/form-data" >
        <x-mary-file wire:model="file" accept="image/png, image/avif, image/jpg, image/jpeg, image/webp" crop-after-change>
            <img src="{{ $user->avatar ?? asset('upload-img-2.jpg') }}" class="h-40 rounded-lg" />
        </x-mary-file>
        <x-slot:actions>
            <x-mary-button label="{{ __('Save') }}" type="submit" class="btn-primary rounded-md text-white" spinner="uploadImg" />
        </x-slot:actions>
    </x-mary-form>
</section>
