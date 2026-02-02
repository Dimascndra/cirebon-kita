<?php

namespace App\Services;

use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CompanyService
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Get list of companies with optional filters
     */
    public function getCompanies(Request $request)
    {
        $filters = $request->only(['search', 'sort']);
        return $this->companyRepository->getPaginated($filters);
    }

    /**
     * Get specific company by slug
     */
    public function getCompanyBySlug($slug)
    {
        return $this->companyRepository->findBySlug($slug);
    }
}
