<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Manpower;
use App\Models\Distribution;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    
    public function index()
    {
        $distributions = Distribution::all();
        return view('Backend.Distribution.Index', compact('distributions'));
    }

 
    public function create()
    {
        $manpower = Manpower::all();
        $menus = Menu::all();
        $snacks_menu = Menu::where('type', 'snacks')->get();
        $lunch_menu = Menu::where('type', 'lunch')->get();
        
        return view('Backend.Distribution.Create', compact('manpower', 'snacks_menu', 'lunch_menu', 'menus'));
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distribution_type' => 'required|string',
            'shift.*' => 'required|string',
            'time_of_day' => 'nullable|string',
            'menu_id.*' => 'required|exists:menus,id',
            'day.*' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $validatedData = $validator->validated();
        $distributionType = $validatedData['distribution_type'];
        $shifts = $validatedData['shift'];
        $menuIds = $validatedData['menu_id'];
        $days = $validatedData['day'];
        $timeOfDay = $validated['time_of_day'] ?? 'Lunch Time';
    
        $existingEntries = [];
        $newEntries = [];
    
        foreach ($shifts as $shift) {
            foreach ($days as $day) {
                foreach ($menuIds as $menuId) {
                    $exists = Distribution::where('shift', $shift)
                        ->where('day', $day)
                        ->where('menu_id', $menuId)
                        ->where(function($query) use ($distributionType, $timeOfDay) {
                            if ($distributionType == 'lunch') {
                                $query->where('distribution_type', $distributionType);
                            } elseif ($distributionType == 'snack') {
                                $query->where('distribution_type', $distributionType)
                                      ->where('time_of_day', $timeOfDay);
                            }
                        })
                        ->exists();
    
                    if ($exists) {
                        $existingEntries[] = [
                            'shift' => $shift,
                            'day' => $day,
                            'menu_id' => $menuId,
                        ];
                    } else {
                        $newEntries[] = [
                            'distribution_type' => $distributionType,
                            'shift' => $shift,
                            'time_of_day' => $timeOfDay,
                            'menu_id' => $menuId,
                            'day' => $day,
                        ];
                    }
                }
            }
        }
    
        if (!empty($newEntries)) {
            Distribution::insert($newEntries);
        }
    
        $message = '';
        if (count($existingEntries) > 0) {
            $message .= 'Some entries already exist: ' . implode(', ', array_map(function($entry) {
                return "Shift: {$entry['shift']}, Day: {$entry['day']}, Menu ID: {$entry['menu_id']}";
            }, $existingEntries)) . '. ';
        }
        if (!empty($newEntries)) {
            $message .= 'Distribution records created successfully.';
        } else if (count($existingEntries) === 0) {
            $message .= 'No new records were created.';
        }
        return redirect()->route('distribution.index')->with('success', $message);
    }
    


    public function edit($id)
    {
        $distribution = Distribution::findOrFail($id);
        $manpower = Manpower::all();
        $menus = Menu::all();
        $snacks_menu = Menu::where('type', 'snacks')->get();
        $lunch_menu = Menu::where('type', 'lunch')->get();
        return view('Backend.Distribution.Edit', compact('distribution', 'manpower', 'snacks_menu', 'lunch_menu', 'menus'));
    }

   
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'distribution_type' => 'required|string',
            'shift.*' => 'required|string',
            'time_of_day' => 'nullable|string',
            'menu_id.*' => 'required|exists:menus,id',
            'day.*' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $validatedData = $validator->validated();
        $distributionType = $validatedData['distribution_type'];
        $shifts = $validatedData['shift'];
        $menuIds = $validatedData['menu_id'];
        $days = $validatedData['day'];
        $timeOfDay = $validatedData['time_of_day'] ?? 'Lunch Time';
    
        $distribution = Distribution::findOrFail($id);
    
        $newEntries = [];
        foreach ($shifts as $shift) {
            foreach ($days as $day) {
                foreach ($menuIds as $menuId) {
                    $exists = Distribution::where('shift', $shift)
                        ->where('day', $day)
                        ->where('menu_id', $menuId)
                        ->where(function ($query) use ($distributionType, $timeOfDay) {
                            if ($distributionType == 'lunch') {
                                $query->where('distribution_type', $distributionType);
                            } elseif ($distributionType == 'snack') {
                                $query->where('distribution_type', $distributionType)
                                      ->where('time_of_day', $timeOfDay);
                            }
                        })
                        ->exists();
    
                    if (!$exists) {
                        $newEntries[] = [
                            'distribution_type' => $distributionType,
                            'shift' => $shift,
                            'time_of_day' => $timeOfDay,
                            'menu_id' => $menuId,
                            'day' => $day,
                        ];
                    }
                }
            }
        }
            Distribution::where('distribution_type', $distributionType)
                    ->where('time_of_day', $timeOfDay)
                    ->where('shift', $distribution->shift)
                    ->where('day', $distribution->day)
                    ->where('menu_id', $distribution->menu_id)
                    ->delete();
    
        if (!empty($newEntries)) {
            Distribution::insert($newEntries);
        }
        $message = 'Distribution records updated successfully.';
        return redirect()->route('distribution.index')->with('success', $message);
       }


    public function destroy($id)
    {
        $distribution = Distribution::findOrFail($id);
        $distribution->delete();

        return back()->with('success', 'Distribution record deleted successfully.');
    }

   
    public function getMenus(Request $request)
    {
        $distributionType = $request->query('distribution_type');
        $menus = Menu::where('type', $distributionType)->get();
        return response()->json($menus);
    }


    public function getDetails(Request $request)
    {
        $type = $request->input('distribution_type');
        $menus = Menu::where('type', $type)->get(['id', 'name']);
        return response()->json(['menus' => $menus]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Distribution::whereIn('id', $ids)->delete();
        }
        return redirect()->route('distribution.index')->with('success', 'Selected records deleted successfully.');
    }
}
