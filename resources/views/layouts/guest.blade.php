@include('layouts.body-header')

{{-- You could elaborate the layout here --}}
{{-- The important part is to have a different layout from the main app layout --}}
<x-mary-main full-width>
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-mary-main>

@include('layouts.body-footer')
