<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
     
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        Role::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Role criada!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role eliminada!');
    }
}
