<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Manpower;
use Illuminate\Http\Request;

class ManpowerController extends Controller
{
    
    public function index()
    {
        $manpowers = Manpower::all();
        return view('Backend.manpower.index', compact('manpowers'));
    }


    public function edit($id)
    {
        $manpower = Manpower::findOrFail($id);
        return view('Backend.manpower.edit', compact('manpower'));
    }

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
