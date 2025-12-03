<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts.
     */
    public function index(): View
    {
        $accounts = Account::all();
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create(): View
    {
        // Daftar rank
        $ranks = ['Warrior', 'Elite', 'Master', 'Grandmaster', 'Epic', 'Legend', 'Mythic', 'Mythical Glory'];

        // Daftar highest rank dengan kategori
        $highestRanks = [
            'Warrior - GM' => 'Warrior - GM',
            'Epic - Legend' => 'Epic - Legend',
            'Mythic' => 'Mythic',
            'Honor' => 'Honor',
            'Glory' => 'Glory',
            'Immortal < 500' => 'Immortal < 500',
            'Immortal > 500' => 'Immortal > 500',
        ];

        // Ambil semua heroes dari database dengan skins
        $heroesFromDb = Hero::with('skins')->orderBy('hero_name')->get();

        // Format heroes untuk dropdown/checkbox
        $heroes = [];
        $heroesFullData = [];
        foreach ($heroesFromDb as $hero) {
            $heroes[$hero->id] = $hero->hero_name;
            $heroesFullData[$hero->id] = [
                'id' => $hero->id,
                'name' => $hero->hero_name,
                'image' => $hero->hero_image,
            ];
        }

        // Format skins per hero dengan gambar
        $skins = [];
        foreach ($heroesFromDb as $hero) {
            $skins[$hero->id] = $hero->skins->map(function ($skin) {
                return [
                    'id' => $skin->id,
                    'name' => $skin->skin_name,
                    'image' => $skin->skin_image,
                    'category' => $skin->category,
                ];
            })->toArray();
        }

        return view('accounts.create', [
            'ranks' => $ranks,
            'highestRanks' => $highestRanks,
            'heroes' => $heroes,
            'heroesFullData' => $heroesFullData,
            'skins' => $skins
        ]);
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'rank' => 'required',
            'highest_rank' => 'nullable|string',
            'winrate' => 'nullable|numeric|min:0|max:100',
            'total_matches' => 'nullable|integer|min:0',
            'heroes' => 'required|array',
            'skins' => 'nullable|array',
        ]);

        Account::create([
            'rank' => $data['rank'],
            'highest_rank' => $data['highest_rank'] ?? null,
            'winrate' => $data['winrate'] ?? null,
            'total_matches' => $data['total_matches'] ?? null,
            'heroes' => $data['heroes'],
            'skins' => $data['skins'] ?? [],
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account added successfully!');
    }
}
