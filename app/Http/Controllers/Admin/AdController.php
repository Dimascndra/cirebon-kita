<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    protected $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    /**
     * Display ads listing
     */
    public function index()
    {
        return view('admin.ads.index');
    }

    /**
     * Get ads data for datatable (AJAX)
     */
    public function getData(Request $request)
    {
        $ads = $this->adService->getAll(-1); // Get all for client-side datatable

        return response()->json([
            'data' => $ads->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'image' => $ad->image_url,
                    'url' => $ad->url,
                    'placement' => match ($ad->placement) {
                        'header' => 'Header (Top Banner)',
                        'sidebar' => 'Sidebar (Right Column)',
                        'homepage' => 'Homepage Feed',
                        'footer' => 'Footer (Bottom Wide)',
                        default => ucfirst($ad->placement),
                    },
                    'start_date' => $ad->start_date ? $ad->start_date->format('Y-m-d H:i') : '-',
                    'end_date' => $ad->end_date ? $ad->end_date->format('Y-m-d H:i') : '-',
                    'is_active' => $ad->is_active,
                    'clicks' => $ad->clicks,
                    'impressions' => $ad->impressions,
                    'ctr' => $ad->ctr . '%',
                    'status' => $ad->isScheduledActive() ? 'Active' : 'Inactive',
                ];
            })
        ]);
    }

    /**
     * Store new ad
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
            'placement' => 'required|in:header,sidebar,footer,homepage',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            $ad = $this->adService->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Banner ad created successfully',
                'data' => $ad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create banner ad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update ad
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
            'placement' => 'required|in:header,sidebar,footer,homepage',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            $ad = $this->adService->update($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Banner ad updated successfully',
                'data' => $ad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner ad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete ad
     */
    public function destroy($id)
    {
        try {
            $this->adService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Banner ad deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete banner ad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track click and redirect (Public endpoint)
     */
    public function trackClick($id)
    {
        try {
            $ad = $this->adService->trackClick($id);

            if ($ad->url) {
                return redirect()->away($ad->url);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}
