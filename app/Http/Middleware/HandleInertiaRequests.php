<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'admin.app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success')
            ],
            'menus' => [
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'isActive' => $request->routeIs('admin.dashboard'),
                    'isVisible' => true
                ],
                [
                    'label' => 'Permissions',
                    'url' => route('admin.permission.index'),
                    'isActive' => $request->routeIs('admin.permission.*'),
                    'isVisible' => $request->user()?->can('view permissions module'),
                ],
                [
                    'label' => 'Roles',
                    'url' => route('admin.role.index'),
                    'isActive' => $request->routeIs('admin.role.*'),
                    'isVisible' => $request->user()?->can('view roles module'),
                ],
                [
                    'label' => 'Users',
                    'url' => route('admin.user.index'),
                    'isActive' => $request->routeIs('admin.user.*'),
                    'isVisible' => $request->user()?->can('view users module'),
                ],
                [
                    'label' => 'Categories',
                    'url' => route('admin.category.index'),
                    'isActive' => $request->routeIs('admin.category.*'),
                    'isVisible' => $request->user()?->can('view categories module'),
                ],
            ]
        ]);
    }
}
