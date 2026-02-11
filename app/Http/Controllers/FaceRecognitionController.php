<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeFace;
use App\Services\FaceApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaceRecognitionController extends Controller
{
    public function __construct(private FaceApiService $faceApi)
    {
    }

    public function showEnrollForm()
    {
        $this->ensureAdmin();

        $employees = Employee::with('user')
            ->orderBy('employee_code')
            ->get();

        return view('admin.face_enroll', [
            'employees' => $employees,
            'selectedEmployeeId' => session('selected_employee'),
        ]);
    }

    public function enroll(Request $request, int $employeeId)
    {
        $this->ensureAdmin();

        $employee = Employee::findOrFail($employeeId);

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $result = $this->faceApi->enroll($employee->employee_id, $validated['image']);

        if (! $result['ok'] || empty($result['embedding'])) {
            return back()
                ->withErrors(['face' => $result['message'] ?? 'Face enrollment failed.'])
                ->with('selected_employee', $employee->employee_id);
        }

        EmployeeFace::updateOrCreate(
            ['employee_id' => $employee->employee_id],
            [
                'embedding' => $result['embedding'],
                'model_name' => $result['model'] ?? config('services.face_api.model', 'buffalo_l'),
            ]
        );

        return back()
            ->with('success', 'Face enrolled successfully for ' . ($employee->user->name ?? 'employee '.$employee->employee_code))
            ->with('selected_employee', $employee->employee_id);
    }

    public function showVerifyForm()
    {
        $user = Auth::user();

        $employee = $user->employee ?? null;

        if (! $employee) {
            abort(403, 'Only employees can access face verification.');
        }

        $faceData = $employee->faceData;

        return view('employee.face_verify', [
            'employee' => $employee,
            'faceData' => $faceData,
            'verifyResult' => session('verify_result'),
        ]);
    }

    public function verify(Request $request, int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $user = Auth::user();

        $this->ensureAdminOrSelf($user?->employee?->employee_id, $employeeId, $user?->role);

        $faceData = $employee->faceData;

        if (! $faceData) {
            return back()->withErrors(['face' => 'No stored face data for this employee. Please enroll first.']);
        }

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $result = $this->faceApi->verify(
            $employee->employee_id,
            $faceData->embedding,
            $validated['image']
        );

        if (! $result['ok']) {
            return back()->withErrors(['face' => $result['message'] ?? 'Face verification failed.']);
        }

        return back()
            ->with('verify_result', $result)
            ->with('success', $result['message'] ?? 'Verification complete.');
    }

    private function ensureAdmin(): void
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Admin access required.');
        }
    }

    private function ensureAdminOrSelf(?int $authEmployeeId, int $targetEmployeeId, ?string $role): void
    {
        if ($role === 'admin') {
            return;
        }

        if ($authEmployeeId !== $targetEmployeeId) {
            abort(403, 'You can only verify your own face.');
        }
    }
}
