<?php

use App\Http\Controllers\DentalClinicController;
use Illuminate\Support\Facades\Route;

Route::view("/", "welcome");

Route::view("dashboard", "dashboard")
    ->middleware(["auth"])
    ->name("dashboard");

Route::prefix("admin")
    ->middleware(["auth", "verified", "rolemanager:admin"])
    ->group(function () {
        Route::view("dashboard", "dashboard")->name("admin_dashboard");
        Route::view("patients", "admin.patients")->name("patients");
        Route::view("dentists", "admin.dentists")->name("dentists");
        Route::view("lists", "admin.lists")->name("lists");
        Route::view("new", "admin.new-admin")->name("new_admin");
        Route::view("profile", "profile")->name("admin_profile");
    });

Route::prefix("subadmin")
    ->middleware(["auth", "verified", "rolemanager:subadmin"])
    ->group(function () {
        Route::view("dashboard", "dashboard")->name("subadmin_dashboard");
        Route::view("patients", "admin.patients")->name("patients");
        Route::view("dentists", "admin.dentists")->name("dentists");
        Route::view("profile", "profile")->name("subadmin_profile");
    });

Route::prefix("dentist")
    ->middleware(["auth", "verified", "rolemanager:dentist"])
    ->group(function () {
        Route::view("/", "dashboard")->name("dentist_dashboard");
        Route::view("profile", "profile")->name("dentist_profile");
        Route::prefix("clinic")->group(function () {
            Route::view("/", "dentist.clinic")->name("clinic");
            Route::get("/{id?}/services", [
                DentalClinicController::class,
                "index",
            ])
                ->where("id", "[0-9]+")
                ->name("clinic_service");
        });
        Route::view("patients", "dentist.patient")->name("patient");
        Route::view("appointment", "dentist.reservation")->name("reservation");
        Route::view("notifications", "dentist.notification")->name(
            "notification"
        );
    });

Route::prefix("patient")
    ->middleware(["auth", "verified", "rolemanager:patient"])
    ->group(function () {
        Route::view("", "dashboard")->name("patient_dashboard");
        Route::view("profile", "profile")->name("patient_profile");
        Route::view("doctor", "patient.doctor")->name("patient_doctor");
        Route::prefix("clinic")->group(function () {
            Route::view("", "patient.clinic")->name("patient_clinic");
            Route::get("/{clinic_id?}", function (?int $clinic_id) {
                return view("patient.one-clinic");
            })
                ->where("id", "[0-9]+")
                ->name("one_clinic");
            Route::get("/{clinic_id?}/reservation", function (?int $clinic_id) {
                return view("patient.reservations");
            })
                ->where("id", "[0-9]+")
                ->name("clinic_reservation");
        });
    });

require __DIR__ . "/auth.php";
