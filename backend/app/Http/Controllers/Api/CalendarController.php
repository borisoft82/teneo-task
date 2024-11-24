<?php

namespace App\Http\Controllers\Api;

use App\Constants\LogMessages;
use App\Http\Controllers\Controller;
use App\Services\CalendarService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    use ApiResponse;

    public function __construct(private CalendarService $calendarService)
    {}

    public function month(): JsonResponse
    {
        try {
            $days = $this->calendarService->createMonth();
            Log::info(LogMessages::CALENDAR_CREATED);
            return $this->success($days);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }

    public function getAbsenceTypes(): JsonResponse
    {
        try {
            $absenceTypes = $this->calendarService->getAbsenceTypes();
            Log::info(LogMessages::ABSENCE_TYPES_CREATED);
            return $this->success($absenceTypes);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }

    public function createAbsence(Request $request): JsonResponse
    {
        try {
            $this->calendarService->createAbsence($request);
            Log::info(LogMessages::ABSENCE_CREATED);
            return $this->success(['success' => LogMessages::ABSENCE_CREATED]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }

    public function getAbsences(): JsonResponse
    {
        try {
            $absences = $this->calendarService->getAbsences();
            Log::info(LogMessages::ABSENCES_LISTED);
            return $this->success($absences);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }

    public function sendToArchive(): JsonResponse
    {
        try {
            $this->calendarService->archiveDates();
            Log::info(LogMessages::ABSENCES_SAVED_TO_ARCHIVE);
            return $this->success(['success' => LogMessages::ABSENCES_SAVED_TO_ARCHIVE]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }

    public function getArchive(): JsonResponse
    {
        try {
            $archives = $this->calendarService->getArchive();
            Log::info(LogMessages::ARCHIVE_LISTED);
            return $this->success($archives);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }
}
