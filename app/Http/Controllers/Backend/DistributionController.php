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
    /**
     * Display a listing of the distribution records.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $distributions = Distribution::all();
        return view('Backend.Distribution.Index', compact('distributions'));
    }

    /**
     * Show the form for creating a new distribution record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $manpower = Manpower::all();
        $menus = Menu::all();
        $snacks_menu = Menu::where('type', 'snacks')->get();
        $lunch_menu = Menu::where('type', 'lunch')->get();
        
        return view('Backend.Distribution.Create', compact('manpower', 'snacks_menu', 'lunch_menu', 'menus'));
    }

    /**
     * Store a newly created distribution record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate input data
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
        $timeOfDay = $validatedData['time_of_day'];
    
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
    
        // Create new entries
        if (!empty($newEntries)) {
            Distribution::insert($newEntries);
        }
    
        // Prepare message
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
    


    /**
     * Show the form for editing the specified distribution record.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $distribution = Distribution::findOrFail($id);
        $manpower = Manpower::all();
        $menus = Menu::all();
        $snacks_menu = Menu::where('type', 'snacks')->get();
        $lunch_menu = Menu::where('type', 'lunch')->get();

        return view('Backend.Distribution.Edit', compact('distribution', 'manpower', 'snacks_menu', 'lunch_menu', 'menus'));
    }

    /**
     * Update the specified distribution record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
{
    // Validate input data
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
    $timeOfDay = $validatedData['time_of_day'];

    $distribution = Distribution::findOrFail($id);
    $distribution->update([
        'distribution_type' => $distributionType,
        'time_of_day' => $timeOfDay,
    ]);

    // Delete existing records for this distribution
    Distribution::where('distribution_type', $distributionType)
                ->where('time_of_day', $timeOfDay)
                ->where('shift', $distribution->shift)
                ->where('day', $distribution->day)
                ->where('menu_id', $distribution->menu_id)
                ->delete();

    foreach ($shifts as $shift) {
        foreach ($days as $day) {
            foreach ($menuIds as $menuId) {
                Distribution::create([
                    'distribution_type' => $distributionType,
                    'shift' => $shift,
                    'time_of_day' => $timeOfDay,
                    'menu_id' => $menuId,
                    'day' => $day,
                ]);
            }
        }
    }

    return redirect()->route('distribution.index')->with('success', 'Distribution records updated successfully.');
}


    /**
     * Remove the specified distribution record from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $distribution = Distribution::findOrFail($id);
        $distribution->delete();

        return back()->with('success', 'Distribution record deleted successfully.');
    }

    /**
     * Get menus based on the distribution type.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenus(Request $request)
    {
        $distributionType = $request->query('distribution_type');

        $menus = Menu::where('type', $distributionType)->get();

        return response()->json($menus);
    }

    /**
     * Get details for the distribution type.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
