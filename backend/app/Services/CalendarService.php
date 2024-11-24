<?php

namespace App\Services;

use App\Constants\GlobalDefinitions;
use App\Models\Absence;
use App\Models\Calendar;
use App\Models\Archive;
use Carbon\Carbon;

class CalendarService 
{
    public function createMonth(): array
    {
        $days = [];

        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $monthDays = Carbon::create($year, $month, 1)->daysInMonth;

        for ($day = 1; $day <= $monthDays; $day++) {
            $date = Carbon::create($year, $month, $day);

            $days[] = [
                'date' => $date->toDateString(),
                'is_weekend' => in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]),
            ];
        }

        return $days;
    }

    public function createAbsence($request) 
    {
        $absence = $request->input('absence');

        $startDate = Carbon::parse($absence['start_date'])->startOfDay();
        $endDate = Carbon::parse($absence['end_date'])->startOfDay();
        $absence_id = $absence['absence_id'];

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        for ($date = $start; $date->lte($end); $date->addDay()) {

            $absencePeriod = (($startDate->diffInDays($endDate) + 1) <= GlobalDefinitions::DAYS) 
                            ? GlobalDefinitions::DAYS_LESS 
                            : GlobalDefinitions::DAYS_MORE;

            Calendar::create([
                'date' => $date, 
                'absence_id' => $absence_id,
                'absence_period' => $absencePeriod
            ]);
        }
    }

    public function getAbsenceTypes()
    {
        return Absence::query()
            ->select('id', 'name')
            ->get();
    }

    public function getAbsences()
    {
        return Calendar::with(['absence' => function($q) {
                    $q->select('id', 'name');
                }])->get();
    }

    public function archiveDates() 
    {
        $dates = Calendar::query()
                ->select('date', 'absence_id')
                ->get();

        $maxOrderNo = Archive::max('order');
        $newOrderNo = $maxOrderNo + 1;

        foreach ($dates as $date) {
            Archive::create([
                'date' => $date->date,
                'absence_id' => $date->absence_id,
                'order' => $newOrderNo,
            ]);
        }
    }

    public function getArchive() 
    {
        return Archive::with(['absence' => function($q) {
                    $q->select('id', 'name');
                }])->get();
    }
}