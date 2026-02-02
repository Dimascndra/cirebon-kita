<?php

namespace App\Repositories;

use App\Models\Ad;
use Illuminate\Support\Facades\Cache;

class AdRepository
{
    protected $ttl = 3600;

    public function getActive()
    {
        return Cache::remember("ads.active", $this->ttl, function () {
            return Ad::active()->get();
        });
    }
}
