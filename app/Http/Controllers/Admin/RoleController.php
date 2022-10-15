<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private string $routeResourceName = 'role';

    public function __construct()
    {
        $this->middleware('can:view roles list')->only('index');
        $this->middleware('can:create role')->only(['create', 'store']);
        $this->middleware('can:edit role')->only(['edit', 'update', 'attachPermission', 'detachPermission']);
        $this->middleware('can:delete role')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $roles = Role::query()
            ->select(['id', 'name', 'created_at'])
            ->when($request->name, fn ($builder, $name) => $builder->where('name', 'like', "%$name%"))
            ->latest('id')
            ->paginate(10);
        return Inertia::render('Role/Index', [
            'title' => 'Roles',
            'items' => RoleResource::collection($roles),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name'
                ],
                [
                    'label' => 'Created At',
                    'name' => 'created_at'
                ],
                [
                    'label' => 'Actions',
                    'name' => 'actions'
                ]
            ],
            'filters' => (object)$request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                'create' => $request->user()->can('create role')
            ]
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Role/Create', [
            'edit' => false,
            'title' => 'Create Role',
            'routeResourceName' => $this->routeResourceName
        ]);
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->validated());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        $role->load(['permissions:id,name']);

        return Inertia::render('Role/Create', [
            'edit' => true,
            'title' => 'Edit Role',
            'item' => new RoleResource($role),
            'routeResourceName' => $this->routeResourceName,
            'permissions' => PermissionResource::collection(Permission::get(['id', 'name']))
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }

    public function attachPermission(Request $request)
    {
        $permission = Permission::find($request->permission_id);
        $permission->assignRole($request->role_id);

        return back()->with('success', 'Permission attached successfully.');
    }

    public function detachPermission(Request $request)
    {
        $permission = Permission::find($request->permission_id);
        $permission->removeRole($request->role_id);

        return back()->with('success', 'Permission detached successfully.');
    }
}
