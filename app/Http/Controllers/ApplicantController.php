<?php

namespace App\Http\Controllers;

use App\Models\Job;
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
     * Submit job application
     */
    public function store(Request $request, Job $job)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        try {
            $application = $this->applicantService->apply(
                $job,
                auth()->user(),
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully! We will review your application soon.',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * User's own applications
     */
    public function index()
    {
        return view('spa');
    }

    /**
     * Show single application
     */
    public function show($id)
    {
        return view('spa');
    }

    public function list(Request $request)
    {
        $applications = $request->user()
            ->jobApplications()
            ->with('job.company')
            ->latest('applied_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $applications,
        ]);
    }

    public function detail(Request $request, $id)
    {
        $application = $request->user()
            ->jobApplications()
            ->with('job.company')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $application,
        ]);
    }
}
