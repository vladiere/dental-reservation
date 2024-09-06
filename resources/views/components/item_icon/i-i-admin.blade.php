<x-mary-menu-item title="Patients" icon="o-user-group" link="{{ route('patients') }}" />
<x-mary-menu-item title="Dentists" icon="o-eye-dropper" link="{{ route('dentists') }}" />
<x-mary-menu-sub title="Receptionist" icon="ri.admin-line">
    <x-mary-menu-item title="Receptionists List" icon="o-list-bullet" link="{{ route('list-receptionist') }}" />
    <x-mary-menu-item title="Register Receptionist" icon="ri.user-add-line" link="{{ route('new_receptionist') }}" />
</x-mary-menu-sub>
