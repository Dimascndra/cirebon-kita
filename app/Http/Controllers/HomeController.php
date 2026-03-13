<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index()
    {
        return view('spa');
    }

    private function jsonResponse($data, $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function hero()
    {
        $data = $this->homeService->getHero();
        return $this->jsonResponse($data);
    }

    public function news()
    {
        $data = $this->homeService->getLatestNews();
        return $this->jsonResponse($data);
    }

    public function jobs()
    {
        $data = $this->homeService->getLatestJobs();
        return $this->jsonResponse($data);
    }

    public function categories()
    {
        $data = $this->homeService->getCategories();
        return $this->jsonResponse($data);
    }

    public function companies()
    {
        $data = $this->homeService->getFeaturedCompanies();
        return $this->jsonResponse($data);
    }

    public function banners()
    {
        $data = $this->homeService->getBanners();
        return $this->jsonResponse($data);
    }
}
