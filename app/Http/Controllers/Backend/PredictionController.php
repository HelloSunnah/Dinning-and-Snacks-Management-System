<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manpower;
use App\Models\Distribution;
use App\Models\Menu;

class PredictionController extends Controller
{
    public function index()
    {
        return view('Backend.Prediction.index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'distribution_type' => 'required|in:lunch,snack',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);
    
        $distributionType = $request->input('distribution_type');
        $day = $request->input('day');
    
        $response = [
            'unique_shifts' => [],
            'morning_menu_items' => [],
            'afternoon_menu_items' => [],
            'menu_items' => [],
        ];
    
        if ($distributionType === 'snack') {
            $distributions = Distribution::where('distribution_type', 'snack')
                ->where('day', $day)
                ->get();
    
            $morningShifts = $distributions->where('time_of_day', 'morning')->pluck('shift')->unique();
            $afternoonShifts = $distributions->where('time_of_day', 'afternoon')->pluck('shift')->unique();
    
            $morningShiftDetails = Manpower::whereIn('shift', $morningShifts)->get();
            $afternoonShiftDetails = Manpower::whereIn('shift', $afternoonShifts)->get();
    
            $response['unique_shifts'] = $morningShiftDetails->merge($afternoonShiftDetails);

            
            $response['morning_menu_items'] = $distributions->where('time_of_day', 'morning')->groupBy('menu_id')->map(function ($group) use ($morningShiftDetails) {
                $menu = $group->first()->menu;
                $totalMembers = $morningShiftDetails->whereIn('shift', $group->pluck('shift')->unique())->sum('member');
                $quantity = $menu->quantity_per_person * $totalMembers;
                $unit = $menu->unit === 'kg' ? 'kg' : 'pcs'; 
                $formattedQuantity = $menu->unit === 'kg' ? number_format($quantity / 1000, 2) : $quantity; // Convert to kg if unit is kg
                return [
                    'menu_name' => $menu->name,
                    'quantity' => $formattedQuantity,
                    'unit' => $unit,
                ];
            })->values();
    
            $response['afternoon_menu_items'] = $distributions->where('time_of_day', 'afternoon')->groupBy('menu_id')->map(function ($group) use ($afternoonShiftDetails) {
                $menu = $group->first()->menu;
                $totalMembers = $afternoonShiftDetails->whereIn('shift', $group->pluck('shift')->unique())->sum('member');
                $quantity = $menu->quantity_per_person * $totalMembers;
                $unit = $menu->unit === 'kg' ? 'kg' : 'pcs'; 
                $formattedQuantity = $menu->unit === 'kg' ? number_format($quantity / 1000, 2) : $quantity; 
                return [
                    'menu_name' => $menu->name,
                    'quantity' => $formattedQuantity,
                    'unit' => $unit,
                ];
            })->values();
    
        } elseif ($distributionType === 'lunch') {
            $distributions = Distribution::where('distribution_type', 'lunch')
                ->where('day', $day)
                ->get();
    
            $uniqueShifts = $distributions->pluck('shift')->unique();
            $shiftDetails = Manpower::whereIn('shift', $uniqueShifts)->get();
            $totalMembers = $shiftDetails->sum('member');
    
            $response['unique_shifts'] = $shiftDetails;
    
            $response['menu_items'] = $distributions->groupBy('menu_id')->map(function ($group) use ($totalMembers) {
                $menu = $group->first()->menu;
                $quantity = $menu->quantity_per_person * $totalMembers;
                $unit = $menu->unit === 'kg' ? 'kg' : 'pcs'; // Use 'kg' if the unit is kg, otherwise 'pcs'
                $formattedQuantity = $menu->unit === 'kg' ? number_format($quantity / 1000, 2) : $quantity; // Convert to kg if unit is kg
                return [
                    'menu_name' => $menu->name,
                    'quantity' => $formattedQuantity,
                    'unit' => $unit,
                ];
            })->values();
        }
    
        return response()->json($response);
    }
}    