<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Employee;
use App\Models\EmployeeFaceTemplate;
use App\Services\FaceService;
use Illuminate\Support\Facades\Storage;

class EmployeeFaceController extends Controller
{
    public function __construct(private FaceService $faceService)
    {
    }

    public function enrollForm()
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403, 'Only employees can enroll face data.');

        $templates = $employee->faceTemplates()->latest()->get();

        return view('employee.face_enroll', compact('employee', 'templates'));
    }

    public function enroll(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403, 'Only employees can enroll face data.');

        $validated = $request->validate([
            'images' => ['required', 'array', 'min:3', 'max:5'],
            'images.*' => ['file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $images = $request->file('images');
        $logContext = ['employee_id' => $employee->employee_id];

        $result = $this->faceService->enroll($employee->employee_id, $images);

        if (! $result['ok']) {
            Log::warning('face_enroll_failed', $logContext + ['message' => $result['message'] ?? '']);
            return back()->withErrors(['face' => $result['message'] ?? 'Enrollment failed.'])->withInput();
        }

        $imgIndex = 0;
        foreach ($result['embeddings'] as $embedding) {
            $image = $images[$imgIndex] ?? null;
            $storedPath = null;
            if ($image) {
                $storedPath = $image->store('face_templates', 'public');
            }

            EmployeeFaceTemplate::create([
                'employee_id' => $employee->employee_id,
                'embedding' => $embedding,
                'image_path' => $storedPath,
                'is_active' => true,
                'approved_by' => null,
                'approved_at' => null,
            ]);

            $imgIndex++;
        }

        Log::info('face_enroll_success', $logContext + ['count' => count($result['embeddings'])]);

        return redirect()->route('employee.face.enroll')->with('success', 'Face templates enrolled successfully.');
    }

    public function destroy(EmployeeFaceTemplate $template)
    {
        $employee = Auth::user()->employee;
        abort_if(! $employee, 403, 'Only employees can manage their face data.');
        abort_if($template->employee_id !== $employee->employee_id, 403, 'Not your template.');

        if ($template->image_path && Storage::disk('public')->exists($template->image_path)) {
            Storage::disk('public')->delete($template->image_path);
        }

        $template->delete();

        return redirect()->route('employee.face.enroll')->with('success', 'Template removed.');
    }
}
