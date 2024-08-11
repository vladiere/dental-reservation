<?php

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
        Route::view("", "dashboard")->name("dentist_dashboard");
        Route::view("profile", "profile")->name("dentist_profile");
    });

Route::prefix("patient")
    ->middleware(["auth", "verified", "rolemanager:patient"])
    ->group(function () {
        Route::view("", "dashboard")->name("patient_dashboard");
        Route::view("profile", "profile")->name("patient_profile");
    });

require __DIR__ . "/auth.php";
