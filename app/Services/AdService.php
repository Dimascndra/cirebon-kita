<?php

namespace App\Services;

use App\Models\Ad;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdService
{
    /**
     * Get all ads with pagination
     */
    public function getAll($perPage = 10)
    {
        if ($perPage == -1) {
            return Ad::latest()->get();
        }
        return Ad::latest()->paginate($perPage);
    }

    /**
     * Get active ads by placement
     */
    public function getActiveAds($placement = null)
    {
        return Ad::active($placement)->latest()->get();
    }

    /**
     * Create new ad
     */
    public function create(array $data)
    {
        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return Ad::create($data);
    }

    /**
     * Update ad
     */
    public function update($id, array $data)
    {
        $ad = Ad::findOrFail($id);

        // Handle image replacement
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image
            if ($ad->image) {
                $this->deleteImage($ad->image);
            }
            // Upload new image
            $data['image'] = $this->uploadImage($data['image']);
        } else {
            // Keep existing image
            unset($data['image']);
        }

        $ad->update($data);
        return $ad;
    }

    /**
     * Delete ad
     */
    public function delete($id)
    {
        $ad = Ad::findOrFail($id);

        // Delete image file
        if ($ad->image) {
            $this->deleteImage($ad->image);
        }

        $ad->delete();
        return true;
    }

    /**
     * Track click
     */
    public function trackClick($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->increment('clicks');
        return $ad;
    }

    /**
     * Track impression
     */
    public function trackImpression($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->increment('impressions');
        return $ad;
    }

    /**
     * Upload image
     */
    private function uploadImage(UploadedFile $file)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('ads', $filename, 'public');
        return $path;
    }

    /**
     * Delete image
     */
    private function deleteImage($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * Get statistics
     */
    public function getStats()
    {
        return [
            'total_ads' => Ad::count(),
            'active_ads' => Ad::where('is_active', true)->count(),
            'total_clicks' => Ad::sum('clicks'),
            'total_impressions' => Ad::sum('impressions'),
            'avg_ctr' => $this->calculateAverageCtr(),
        ];
    }

    /**
     * Calculate average CTR
     */
    private function calculateAverageCtr()
    {
        $totalImpressions = Ad::sum('impressions');
        $totalClicks = Ad::sum('clicks');

        if ($totalImpressions == 0) {
            return 0;
        }

        return round(($totalClicks / $totalImpressions) * 100, 2);
    }
}
