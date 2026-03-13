<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
        $this->middleware('permission:job-list|job-create|job-edit|job-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:job-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:job-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:job-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('spa');
    }

    public function getData()
    {
        $jobs = $this->jobService->getAll(-1);

        return response()->json([
            'data' => $jobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'company' => $job->company ? $job->company->name : '-',
                    'location' => $job->location,
                    'type' => $job->type,
                    'status' => ucfirst($job->status),
                    'created_at' => $job->created_at ? $job->created_at->format('d M Y') : '-',
                    'actions' => '
                        <a href="' . route('admin.jobs.edit', $job->id) . '" class="btn btn-sm btn-clean btn-icon" title="Edit">
                            <i class="la la-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-clean btn-icon" title="Delete" onclick="deleteJob(' . $job->id . ')">
                            <i class="la la-trash"></i>
                        </button>
                    '
                ];
            })
        ]);
    }

    public function create()
    {
        return view('spa');
    }

    public function show($id)
    {
        return view('spa');
    }

    public function meta()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'companies' => Company::all(['id', 'name']),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'status' => 'required|in:active,closed',
            'description' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->jobService->create($request->all());
            return response()->json(['message' => 'Job created successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $job = \App\Models\Job::findOrFail($id);
        return view('spa');
    }

    public function detail($id)
    {
        $job = \App\Models\Job::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $job,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'status' => 'required|in:active,closed',
            'description' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->jobService->update($id, $request->all());
            return response()->json(['message' => 'Job updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->jobService->delete($id);
            return response()->json(['message' => 'Job deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
