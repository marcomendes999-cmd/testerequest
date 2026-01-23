<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.manage-roles', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);
        return redirect()->route('users.roles.edit', $user)->with('success', 'Roles atualizados com sucesso!');
    }
}
