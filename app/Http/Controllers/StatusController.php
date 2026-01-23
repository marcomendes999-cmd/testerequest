<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::orderBy('ordem', 'asc')->paginate(10);
        return view('statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('statuses')],
            'ordem' => 'string|max:3',
        ]);

        Status::create($validatedData);

        return redirect()->route('statuses.index')->with('success', 'Status criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return view('statuses.show', compact('status'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        return view('statuses.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('statuses')->ignore($status->id)],
            'ordem' => 'string|max:3',
        ]);

        $status->update($validatedData);

        return redirect()->route('statuses.index')->with('success', 'Status atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();
        return redirect()->route('statuses.index')->with('success', 'Status excluído com sucesso!');
    }
}
