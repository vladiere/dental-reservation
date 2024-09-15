<?php

namespace App\Http\Controllers;

use App\Models\WebNotification;
use Illuminate\Http\Request;

class WebNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(
        $appointment_id,
        $web_message,
        $web_datetime,
        $notif_status = 0
    ) {
        // "appointment_id",
        // "web_message",
        // "web_date_time",
        // "notif_status",
        $result = WebNotification::create([
            "appointment_id" => $appointment_id,
            "web_message" => $web_message,
            "web_date_time" => $web_datetime,
            "notif_status" => $notif_status,
        ]);

        if ($result) {
            return true;
        }

        return false;
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
    public function show(WebNotification $webNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebNotification $webNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebNotification $webNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebNotification $webNotification)
    {
        //
    }
}
