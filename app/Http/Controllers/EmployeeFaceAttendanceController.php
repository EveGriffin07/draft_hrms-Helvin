<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\FaceService;

class EmployeeFaceAttendanceController extends Controller
{
    public function __construct(private FaceService $faceService)
    {
    }

    public function show()
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403, 'Only employees can verify attendance.');

        $hasEnrollment = $employee->faceTemplates()->exists();

        return view('employee.attendance_face', compact('employee', 'hasEnrollment'));
    }

    public function check(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403, 'Only employees can verify attendance.');

        $validated = $request->validate([
            'frame' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'challenge' => ['required', 'string'],
        ]);

        $logContext = ['employee_id' => $employee->employee_id];

        $match = $this->faceService->match($employee->employee_id, $validated['frame']);

        if (! $match['ok']) {
            Log::warning('face_attendance_failed', $logContext + ['message' => $match['message'] ?? '']);
            return back()->withErrors(['face' => $match['message'] ?? 'Verification failed.']);
        }

        $matched = $match['matched'] ?? false;
        $score = $match['score'] ?? null;

        Attendance::create([
            'employee_id' => $employee->employee_id,
            'date' => now()->toDateString(),
            'status' => $matched ? 'present' : 'absent',
            'verified_method' => 'face',
            'verify_score' => $score,
        ]);

        Log::info('face_attendance_attempt', $logContext + ['score' => $score, 'matched' => $matched]);

        if (! $matched) {
            return back()->withErrors(['face' => 'Face did not match. Score: '.number_format($score, 3)]);
        }

        return back()->with('success', 'Attendance recorded via face. Score: '.number_format($score, 3));
    }
}
