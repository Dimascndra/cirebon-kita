<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Services\ApplicantService;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function __construct(
        protected ApplicantService $applicantService
    ) {
        // Middleware handled in routes
    }

    /**
     * List all applicants for company's jobs
     */
    public function index(Request $request)
    {
        $query = JobApplication::with(['user', 'job'])
            ->whereHas('job', function ($q) {
                // TODO: Add company_id filter when Company model is ready
                // $q->where('company_id', auth()->user()->company_id);
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by job
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->latest('applied_at')->paginate(20);

        $statuses = ['pending', 'reviewing', 'shortlisted', 'rejected', 'accepted'];

        return view('company.applicants.index', compact('applications', 'statuses'));
    }

    /**
     * Show applicant details
     */
    public function show(JobApplication $application)
    {
        // TODO: Add authorization check
        // $this->authorize('view', $application);

        $application->load(['user', 'job']);

        return view('company.applicants.show', compact('application'));
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        // TODO: Add authorization check
        // $this->authorize('update', $application);

        $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,rejected,accepted',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->applicantService->updateStatus(
            $application,
            $request->status,
            $request->notes
        );

        return redirect()
            ->back()
            ->with('success', 'Application status updated successfully');
    }

    /**
     * Download CV
     */
    public function downloadCv(JobApplication $application)
    {
        // TODO: Add authorization check
        // $this->authorize('view', $application);

        return response()->download(
            storage_path('app/public/' . $application->cv_path)
        );
    }
}
