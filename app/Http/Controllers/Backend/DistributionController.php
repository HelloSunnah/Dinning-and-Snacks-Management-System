<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Manpower;
use Illuminate\Http\Request;
use App\Models\Distribution;
use App\Models\Menu;

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
            $menus = menu::all();

            $snacks_menu=Menu::where('type','=>','snacks')->get();
            $lunch_menu=Menu::where('type','=>','lunch')->get();
            
            return view('Backend.Distribution.Create', compact('manpower','snacks_menu','lunch_menu','menus'));  
        
        }
        public function store(Request $request)
        {

            $validatedData = $request->validate([
                'distribution_type' => 'required|string',
                'shift' => 'required|string',
                'time_of_day' => 'nullable|string',
                'menu_id' => 'required|exists:menus,id',
                'day' => 'required|string',
               ]);
        
            $distributionType = $validatedData['distribution_type'];
            $shift = $validatedData['shift'];
            $menuId = $validatedData['menu_id'];
            $day = $validatedData['day'];
            $timeOfDay = $validatedData['time_of_day'];
        
            $existingDistribution = Distribution::where('shift', $shift)
                ->where('day', $day)
                ->where(function($query) use ($distributionType, $menuId, $timeOfDay) {
                    if ($distributionType == 'lunch') {
                        $query->where('distribution_type', $distributionType)
                              ->where('menu_id', $menuId);
                    } else if ($distributionType == 'snack') {
                        $query->where('distribution_type', $distributionType)
                              ->where('menu_id', $menuId)
                              ->where('time_of_day', $timeOfDay);
                    }
                })
                ->exists();
        
            if ($existingDistribution) {
                return back()->withErrors(['message' => 'This shift has already taken this item for the selected time and day.']);
            }
        
            Distribution::create([
                'distribution_type' => $distributionType,
                'shift' => $shift,
                'time_of_day' => $timeOfDay,
                'menu_id' => $menuId,
                'day' => $day,
            ]);
        
            return redirect()->route('distribution.index')->with('success', 'Distribution record created successfully.');
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
            $validatedData = $request->validate([
                'distribution_type' => 'required|string',
                'shift' => 'required|string',
                'time_of_day' => 'nullable|string',
                'menu_id' => 'required|exists:menus,id',
                'day' => 'required|string',
            ]);
        
            $distribution = Distribution::findOrFail($id);
        
            $existingDistribution = Distribution::where('shift', $request->shift)
                ->where('day', $request->day)
                ->where(function($query) use ($request) {
                    if ($request->distribution_type == 'lunch') {
                        $query->where('distribution_type', $request->distribution_type)
                              ->where('menu_id', $request->menu_id);
                    } else if ($request->distribution_type == 'snack') {
                        $query->where('distribution_type', $request->distribution_type)
                              ->where('menu_id', $request->menu_id)
                              ->where('time_of_day', $request->time_of_day);
                    }
                })
                ->where('id', '!=', $id)  // Ensure that the current record is excluded from the check
                ->exists();
        
            if ($existingDistribution) {
                return back()->withErrors(['message' => 'This shift has already taken this item for the selected time and day.']);
            }
        
            $distribution->update($validatedData);
        
            return redirect()->route('distribution.index')->with('success', 'Distribution record updated successfully.');
        }


        public function getMenus(Request $request)
        {
            $distributionType = $request->query('distribution_type');
        
            if ($distributionType === 'lunch') {
                $menus=Menu::where('type','=>','lunch')->get();
            } elseif ($distributionType === 'snack') {
                $menus=Menu::where('type','=>','snacks')->get();
            } else {
                $menus = []; 
            }
        
            return response()->json($menus);
        }

        public function getDetails(Request $request)
        {
            $type = $request->input('distribution_type');
        
            $menus = Menu::where('type', $type)->get(['id', 'name']);
        
            return response()->json(['menus' => $menus]);
        }


        
        public function destroy($id)
        {
            $distribution = Distribution::findOrFail($id);
            $distribution->delete();
    
            return redirect()->route('distribution.index')->with('success', 'Distribution record deleted successfully.');
        }


    }
    