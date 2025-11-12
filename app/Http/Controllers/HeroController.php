<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;

class HeroController extends Controller
{
    /**
     * Display a listing of the heroes.
     */
    public function index()
    {
        $heroes = Hero::with('skins')
            ->orderBy('hero_name')
            ->paginate(24);

        return view('heroes.index', compact('heroes'));
    }

    /**
     * Display the specified hero.
     */
    public function show($id)
    {
        $hero = Hero::with('skins')->findOrFail($id);
        return view('heroes.show', compact('hero'));
    }

    /**
     * Search heroes by name.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $heroes = Hero::with('skins')
            ->where('hero_name', 'like', '%' . $search . '%')
            ->orderBy('hero_name')
            ->paginate(24);

        return view('heroes.index', compact('heroes', 'search'));
    }
}
