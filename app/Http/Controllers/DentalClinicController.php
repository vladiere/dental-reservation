<?php

namespace App\Http\Controllers;

use App\Models\DentalClinic;
use Illuminate\Http\Request;

class DentalClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?int $id)
    {
        $result = DentalClinic::where("id", "=", $id)->get();

        return view("dentist.service", ["clinic" => $result]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DentalClinic $dentalClinic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DentalClinic $dentalClinic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DentalClinic $dentalClinic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DentalClinic $dentalClinic)
    {
        //
    }
}
