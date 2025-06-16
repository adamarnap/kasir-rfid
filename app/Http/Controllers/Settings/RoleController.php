<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('roles.read');

        $roles = Role::all();
        return view('settings.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->setRule('roles.create');

        $create = true;
        return view('settings.role.edit', compact('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('roles.create');

        $request->validate([
            'name' => 'required',
        ]);
        //
        Role::create(['name' => $request->name]);
        return redirect()->back()->with('success', __('app.notif.successSave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->setRule('roles.update');

        return view('settings.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->setRule('roles.update');

        $request->validate([
            'name' => 'required',
        ]);
        //
        $role->name = $request->name;
        $role->save();
        return redirect()->back()->with('success', __('app.notif.successUpdate'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->setRule('roles.delete');

        $role->delete();
        return redirect('roles')->with('success', __('app.notif.successDelete'));
    }

    /**
     * Display a listing of the permission.
     */
    public function show(Role $role)
    {
        $this->setRule('roles.read');

        $permissions = $role->permissions->pluck('name')->toArray();
        $navigations = Navigation::where('parent_id', null)->with('child')->get();
        return view('settings.role.show', compact('role', 'permissions', 'navigations'));
        
    }

    public function givePermission(Request $request, Role $role)
    {
        $this->setRule('roles.update');
        // remove premission
        foreach ($request->permission ?? [] as $key => $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
        }
        $role->syncPermissions($request->permission);
        return redirect()->back()->with('success', 'Permission given successfully');
    }
}
