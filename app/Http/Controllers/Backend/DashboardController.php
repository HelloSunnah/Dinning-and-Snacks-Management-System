<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Manpower;
use App\Models\Distribution;

class DashboardController extends Controller
{
    //
    public function index()  {
       
        $menuCount = Menu::count();
        $manpowerCount = Manpower::count();
        $distributionCount = Distribution::count();

        // Example for recent activities
        $recentMenus = Menu::latest()->limit(5)->get();
        $recentManpower = Manpower::latest()->limit(5)->get();
        $recentDistributions = Distribution::latest()->limit(5)->get();

        // Example data for charts
        $lunchCount = Distribution::where('distribution_type', 'lunch')->count();
        $snackCount = Distribution::where('distribution_type', 'snack')->count();

        return view('Backend.Dashboard', compact('menuCount', 'manpowerCount', 'distributionCount', 'recentMenus', 'recentManpower', 'recentDistributions', 'lunchCount', 'snackCount'));
  
    }
    }
