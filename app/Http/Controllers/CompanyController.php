<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the companies.
     */
    public function index(Request $request)
    {
        return view('spa');
    }

    public function list(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->companyService->getCompanies($request),
        ]);
    }

    /**
     * Display the specified company.
     */
    public function show($slug)
    {
        return view('spa');
    }

    public function detail($slug)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->companyService->getCompanyBySlug($slug),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ], 404);
        }
    }
}
