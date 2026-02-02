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
        $companies = $this->companyService->getCompanies($request);
        return view('companies.index', compact('companies'));
    }

    /**
     * Display the specified company.
     */
    public function show($slug)
    {
        $company = $this->companyService->getCompanyBySlug($slug);
        return view('companies.show', compact('company'));
    }
}
