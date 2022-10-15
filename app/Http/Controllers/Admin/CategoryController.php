<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class CategoryController extends Controller
{
    private string $routeResourceName = 'category';

    public function __construct()
    {
        $this->middleware('can:view categorys list')->only('index');
        $this->middleware('can:create category')->only(['create', 'store']);
        $this->middleware('can:edit category')->only(['edit', 'update']);
        $this->middleware('can:delete category')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $categories = Category::query()
            ->select(['id', 'parent_id', 'name', 'created_at', 'active'])
            ->withCount(['children'])
            ->when($request->name, fn ($builder, $name) => $builder->where('name', 'like', "%$name%"))
            ->when(
                $request->active !== null,
                fn ($builder) => $builder->when(
                    $request->active,
                    fn ($builder) => $builder->active(),
                    fn ($builder) => $builder->inactive()
                )
            )
            ->when(
                $request->parentId,
                fn ($builder) => $builder->where('parent_id', $request->parentId),
                fn ($builder) => $builder->root()
            )
            ->latest('id')
            ->paginate(100);
        return Inertia::render('Category/Index', [
            'title' => 'Categories',
            'items' => CategoryResource::collection($categories),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name'
                ],
                [
                    'label' => 'Children',
                    'name' => 'children_count'
                ],
                [
                    'label' => 'Active',
                    'name' => 'active'
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
                'create' => $request->user()->can('create category')
            ],
            'rootCategories' => CategoryResource::collection(Category::root()->get(['id', 'name'])),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Category/Create', [
            'edit' => false,
            'title' => 'Create Category',
            'routeResourceName' => $this->routeResourceName,
            'rootCategories' => CategoryResource::collection(Category::root()->get(['id', 'name'])),
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->safe()->only(['name', 'slug', 'active']);
        $data['parent_id'] = $request->parentId;
        $category = Category::create($data);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Category/Create', [
            'edit' => true,
            'title' => 'Edit Category',
            'item' => new CategoryResource($category),
            'routeResourceName' => $this->routeResourceName,
            'rootCategories' => CategoryResource::collection(Category::root()->get(['id', 'name'])),
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->safe()->only(['name', 'slug', 'active']);
        $data['parent_id'] = $request->parentId;
        $category->update($data);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully');
    }
}
