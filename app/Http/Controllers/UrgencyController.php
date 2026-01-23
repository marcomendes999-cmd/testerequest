<?php

namespace App\Http\Controllers;

use App\Models\Urgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UrgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $urgencies = Urgency::orderBy('ordem', 'asc')->paginate(10);
        return view('urgencies.index', compact('urgencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('urgencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|max:255',
            'ordem' => 'string|max:3',
            'time'  => 'nullable|integer',
        ]);

        Urgency::create($request->all());

        return Redirect::route('urgencies.index')->with('success', 'Urgência criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Urgency $urgency): View
    {
        return view('urgencies.edit', compact('urgency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Urgency $urgency)
    {
        $request->validate([
            'name' => 'required|max:255',
            'ordem' => 'string|max:3',
            'time'  => 'nullable|integer',
        ]);

        $urgency->update($request->all());

        return Redirect::route('urgencies.index')->with('success', 'Urgência atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Urgency $urgency)
    {
        $urgency->delete();

        return Redirect::route('urgencies.index')->with('success', 'Urgência eliminada com sucesso!');
    }
}
