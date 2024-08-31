<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('Backend.Menu.index', compact('menus'));
    }

    public function create()
    {
        return view('Backend.Menu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'quantity_per_person' => 'required|numeric',
        ]);

        Menu::create($validated);

        return redirect()->route('menu.index')->with('success', 'Menu created successfully.');
    }

   

    public function edit(Menu $menu)
    {
        return view('Backend.Menu.Edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'quantity_per_person' => 'required|numeric',
        ]);

        $menu->update($validated);

        return redirect()->route('menu.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu deleted successfully.');
    }
}
