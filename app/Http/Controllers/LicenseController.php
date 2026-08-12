<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licenses = License::latest()->paginate(10);
        return view('licenses.index', compact('licenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('licenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'max_users' => 'required|integer|min:1',
            'expires_at' => 'required|date|after_or_equal:today',
            'ativo' => 'required|boolean',
        ]);

        DB::transaction(function () use ($validatedData) {
            if ($validatedData['ativo']) {
                License::query()->update(['ativo' => false]);
            }

            License::create($validatedData);
        });

        return redirect()->route('licenses.index')->with('success', 'Licença criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(License $license)
    {
        return view('licenses.edit', compact('license'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, License $license)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'max_users' => 'required|integer|min:1',
            'expires_at' => 'required|date',
            'ativo' => 'required|boolean',
        ]);

        DB::transaction(function () use ($license, $validatedData) {
            if ($validatedData['ativo']) {
                License::whereKeyNot($license->id)->update(['ativo' => false]);
            }

            $license->update($validatedData);
        });

        return redirect()->route('licenses.index')->with('success', 'Licença atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(License $license)
    {
        $license->delete();
        return redirect()->route('licenses.index')->with('success', 'Licença excluída com sucesso!');
    }

        /**
     * Displays a list of active users.
     */
    public function activeUsers()
    {
        // Get all users who are currently logged in
        $activeSessions = \DB::table('sessions')
            ->where('user_id', '!=', null)
            ->pluck('user_id');

        $activeUsers = User::whereIn('id', $activeSessions)->get();

        return view('licenses.active-users', compact('activeUsers'));
    }
}
