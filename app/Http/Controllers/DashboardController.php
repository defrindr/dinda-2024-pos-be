<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'reportDownload']);
    }

    public function dataCount()
    {
        $user = auth()->user();

        return ResponseHelper::successWithData(
            DashboardService::get($user)
        );
    }
}
