<x-mary-menu-item title="Patients" icon="o-user-group" link="{{ route('patients') }}" />
<x-mary-menu-item title="Dentists" icon="o-eye-dropper" link="{{ route('dentists') }}" />
<x-mary-menu-item title="Schedules" icon="o-clock" link="#" />
<x-mary-menu-sub title="Admins" icon="ri.admin-line">
    <x-mary-menu-item title="Lists" icon="o-list-bullet" link="{{ route('lists') }}" />
    <x-mary-menu-item title="New Admin" icon="ri.user-add-line" link="{{ route('new_admin') }}" />
</x-mary-menu-sub>
