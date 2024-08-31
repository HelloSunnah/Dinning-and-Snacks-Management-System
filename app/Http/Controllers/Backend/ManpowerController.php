<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Manpower;
use Illuminate\Http\Request;

class ManpowerController extends Controller
{
    /**
     * Display a listing of the manpower records.
     */
    public function index()
    {
        // Retrieve all manpower records from the database
        $manpowers = Manpower::all();
        return view('Backend.manpower.index', compact('manpowers'));
    }

    /**
     * Show the form for editing the specified manpower record.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        // Find the manpower record by its ID or fail if not found
        $manpower = Manpower::findOrFail($id);
        return view('Backend.manpower.edit', compact('manpower'));
    }

    /**
     * Update the specified manpower record in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'shift' => 'required|string|max:255',
            'member' => 'required|integer',
        ]);

        $manpower = Manpower::find($id);

        if (!$manpower) {
            return redirect()->route('manpower.index')->with('error', 'Manpower record not found.');
        }

        $manpower->shift = $request->input('shift');
        $manpower->member = $request->input('member');
        $manpower->save();

        return redirect()->route('manpower.index')->with('success', 'Manpower updated successfully.');
    }
}
