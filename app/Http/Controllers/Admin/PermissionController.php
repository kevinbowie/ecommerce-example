<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private string $routeResourceName = 'permission';

    public function __construct()
    {
        $this->middleware('can:view permissions list')->only('index');
        $this->middleware('can:create permission')->only(['create', 'store']);
        $this->middleware('can:edit permission')->only(['edit', 'update']);
        $this->middleware('can:delete permission')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->select(['id', 'name', 'created_at'])
            ->when($request->name, fn ($builder, $name) => $builder->where('name', 'like', "%$name%"))
            ->latest('id')
            ->paginate(10);
        return Inertia::render('Permission/Index', [
            'title' => 'Permissions',
            'items' => PermissionResource::collection($permissions),
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
                'create' => $request->user()->can('create permission')
            ]
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Permission/Create', [
            'edit' => false,
            'title' => 'Create Permission',
            'routeResourceName' => $this->routeResourceName
        ]);
    }

    public function store(PermissionRequest $request)
    {
        Permission::create($request->validated());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('Permission/Create', [
            'edit' => true,
            'title' => 'Edit Permission',
            'item' => new PermissionResource($permission),
            'routeResourceName' => $this->routeResourceName
        ]);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted successfully');
    }
}
