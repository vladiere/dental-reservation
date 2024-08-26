<x-mary-menu-item title="Doctors" icon="mdi.doctor" link="{{ route('patient_doctor') }}" />
<x-mary-menu-item title="Clinic" icon="bx.clinic" link="{{ route('patient_clinic') }}" />
<x-mary-menu-item title="Notifications" icon="o-bell" link="#" />
<x-mary-menu-sub title="Booking" icon="iconsax.out-reserve">
    <x-mary-menu-item title="Reservation" icon="o-calendar-days" link="#" />
    <x-mary-menu-item title="Solo" icon="fluentui.person-16-o" link="#" />
    <x-mary-menu-item title="Cluster" icon="fluentui.people-queue-24-o" link="#" />
</x-mary-menu-sub>
