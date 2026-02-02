<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Exception;

class ApplicantService
{
    /**
     * Submit job application
     */
    public function apply(Job $job, User $user, array $data): JobApplication
    {
        // Check if already applied
        if ($user->hasAppliedTo($job->id)) {
            throw new Exception('You have already applied to this job');
        }

        // Handle CV upload
        if (!isset($data['cv'])) {
            throw new Exception('CV file is required');
        }

        $cvPath = $data['cv']->store('cvs', 'public');

        // Create application
        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'cv_path' => $cvPath,
            'cover_letter' => $data['cover_letter'] ?? null,
            'status' => 'pending',
        ]);

        // TODO: Send notification to company
        // $this->notifyCompany($application);

        return $application;
    }

    /**
     * Update application status
     */
    public function updateStatus(JobApplication $application, string $status, ?string $notes = null): JobApplication
    {
        $application->update([
            'status' => $status,
            'notes' => $notes,
        ]);

        // TODO: Notify applicant
        // $this->notifyApplicant($application);

        return $application;
    }

    /**
     * Delete application and CV file
     */
    public function deleteApplication(JobApplication $application): bool
    {
        // Delete CV file
        if (Storage::disk('public')->exists($application->cv_path)) {
            Storage::disk('public')->delete($application->cv_path);
        }

        return $application->delete();
    }

    /**
     * Get applications for a job
     */
    public function getJobApplications(Job $job, ?string $status = null)
    {
        $query = $job->applications()->with('user');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Get user's applications
     */
    public function getUserApplications(User $user)
    {
        return $user->jobApplications()->with('job')->latest()->get();
    }
}
