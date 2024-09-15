<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class TimeRange
{
    public static function stringify($start, $end, $glue = " - ")
    {
        // Attempt to parse the start and end
        $start = $start ? rescue(fn() => Carbon::parse($start)) : null;
        $end = $end ? rescue(fn() => Carbon::parse($end)) : null;

        $start24h = $start?->format("H:i");
        $end24h = $end?->format("H:i");

        // If times are identical, return early
        if ($start24h === $end24h) {
            return $start?->format("g:ia");
        }

        // Stringify the start and end
        $startStr = str($start?->format("g:i"));
        $endStr = str($end?->format("g:i"));

        // Extract the periods (am/pm)
        $startPeriod = $start?->format("a");
        $endPeriod = $end?->format("a");

        // Strip redundant :00 from the end of the strings
        $startStr = $startStr->whenEndsWith(
            ":00",
            fn($s) => $s->beforeLast(":00")
        );
        $endStr = $endStr->whenEndsWith(":00", fn($s) => $s->beforeLast(":00"));

        $suffix = "";

        // Only attach individual periods if they differ
        if ($startPeriod !== $endPeriod) {
            $startStr = $startStr->append($startPeriod);
            $endStr = $endStr->append($endPeriod);
        } else {
            $suffix = $endPeriod;
        }

        $combined = collect([$startStr->toString(), $endStr->toString()])
            ->reject(fn($s) => blank($s))
            ->join($glue);

        return $combined . $suffix;
    }

    public static function checkDateTime($dateTime): bool
    {
        $now = Carbon::now();
        $givenDate = Carbon::parse($dateTime);

        // Check if the date is not yesterday
        if ($givenDate->isYesterday()) {
            return false;
        }

        // Check if the time is less than a minute ago
        if ($givenDate->diffInMinutes($now) < 1) {
            return false;
        }

        return true;
    }

    public static function consiseDatetime($value)
    {
        return $value ? Carbon::parse($value)->format("M d, Y h:i A") : null;
    }
}
