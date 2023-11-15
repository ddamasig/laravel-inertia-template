<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $itemsPerPage = $request->get('per_page', 100);
        $breadcrumbs = [
            [
                'title' => 'Permissions',
                'href' => '/management/permissions'
            ]
        ];

        $resources = QueryBuilder::for(Permission::class)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->where('name', 'ilike', "%$value%")
                        ->orWhere('description', 'ilike', "%$value%");
                }),
            ])
            ->allowedSorts(['name', 'description', 'created_at'])
            ->orderBy('name', 'asc')
            ->paginate($itemsPerPage);

        return Inertia::render('Management/Security/Permissions/Index', [
            'breadcrumbs' => $breadcrumbs,
            'resources' => $resources
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Permissions',
                'href' => '/management/permissions'
            ],
            [
                'title' => 'Create',
            ],
        ];

        return Inertia::render('Management/Security/Permissions/Create', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function show(Permission $permission): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Permissions',
                'href' => '/management/permissions'
            ],
            [
                'title' => $permission->name,
            ],
        ];

        return Inertia::render('Management/Security/Permissions/Show', [
            'breadcrumbs' => $breadcrumbs,
            'permission' => $permission,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'name' => 'required|max:512|unique:permissions,name',
            'description' => 'required|max:1024',
        ]);

        try {
            Permission::create($input);
        } catch (\Exception $exception) {
        }
        return to_route('permissions.index');
    }

    public function edit(Request $request, Permission $permission): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Permissions',
                'href' => '/management/permissions'
            ],
            [
                'title' => $permission->name,
                'href' => "/management/permissions/$permission->id"
            ],
            [
                'title' => 'Edit',
            ],
        ];

        return Inertia::render('Management/Security/Permissions/Edit', [
            'breadcrumbs' => $breadcrumbs,
            'permission' => $permission,
        ]);

    }

    public function update(Request $request, Permission $permission)
    {
        $input = $request->validate([
            'name' => "required|max:512|unique:permissions,name,$permission->id",
            'description' => 'required|max:1024',
        ]);

        $parameters = [
            'permission' => $permission->id,
            'errorBags' => [
                'default' => []
            ]
        ];

        $permission->name = $input['name'];
        $permission->description = $input['description'];
        $permission->save();

        return to_route('permissions.show', $parameters);
    }

    public function destroy(Request $request, Permission $permission)
    {
        $permission->delete();
        return redirect()->back();
    }
}
