<?php

use Illuminate\Support\Facades\Route;

Route::view("/", "welcome");

Route::view("dashboard", "dashboard")
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::view("profile", "profile")
    ->middleware(["auth"])
    ->name("profile");

Route::view("patients", "patients")
    ->middleware(["auth"])
    ->name("patients");

Route::view("dentists", "dentists")
    ->middleware(["auth"])
    ->name("dentists");

require __DIR__ . "/auth.php";
