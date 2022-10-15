<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private string $routeResourceName = 'user';

    public function __construct()
    {
        $this->middleware('can:view users list')->only('index');
        $this->middleware('can:create user')->only(['create', 'store']);
        $this->middleware('can:edit user')->only(['edit', 'update']);
        $this->middleware('can:delete user')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'created_at'])
            ->with(['roles:id,name'])
            ->when($request->name, fn ($builder, $name) => $builder->where('name', 'like', "%$name%"))
            ->when($request->email, fn ($builder, $email) => $builder->where('email', 'like', "%$email%"))
            ->when(
                $request->roleId,
                fn ($builder, $roleId) => $builder->whereHas(
                    'roles',
                    fn ($builder) => $builder->where('id', $roleId)
                )
            )
            ->latest('id')
            ->paginate(10);
        return Inertia::render('User/Index', [
            'title' => 'Users',
            'items' => UserResource::collection($users),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name'
                ],
                [
                    'label' => 'Email',
                    'name' => 'email'
                ],
                [
                    'label' => 'Role',
                    'name' => 'role'
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
                'create' => $request->user()->can('create user')
            ],
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('User/Create', [
            'edit' => false,
            'title' => 'Create User',
            'routeResourceName' => $this->routeResourceName,
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),
        ]);
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->safe()->only(['name', 'email', 'password']));
        $user->assignRole($request->roleId);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $user->load(['roles:id']);

        return Inertia::render('User/Create', [
            'edit' => true,
            'title' => 'Edit User',
            'item' => new UserResource($user),
            'routeResourceName' => $this->routeResourceName,
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->safe()->only(['name', 'email', 'password']));
        $user->syncRoles($request->roleId);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }
}
