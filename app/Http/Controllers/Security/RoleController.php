<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $itemsPerPage = $request->get('per_page', 100);
        $breadcrumbs = [
            [
                'title' => 'Roles',
                'href' => '/roles'
            ]
        ];

        $resources = QueryBuilder::for(Role::class)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->where('name', 'ilike', "%$value%")
                        ->orWhere('description', 'ilike', "%$value%");
                }),
            ])
            ->allowedSorts(['name', 'description', 'created_at'])
            ->orderBy('name', 'asc')
            ->paginate($itemsPerPage);

        return Inertia::render('Security/Roles/Index', [
            'breadcrumbs' => $breadcrumbs,
            'resources' => $resources
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Roles',
                'href' => '/roles'
            ],
            [
                'title' => 'Create',
            ],
        ];

        return Inertia::render('Security/Roles/Create', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function show(Role $role): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Roles',
                'href' => '/roles'
            ],
            [
                'title' => $role->name,
            ],
        ];

        return Inertia::render('Security/Roles/Show', [
            'breadcrumbs' => $breadcrumbs,
            'role' => $role,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'name' => 'required|max:512|unique:roles,name',
            'description' => 'required|max:1024',
        ]);

        try {
            Role::create($input);
        } catch (\Exception $exception) {
        }
        return to_route('roles.index');
    }

    public function edit(Request $request, Role $role): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Roles',
                'href' => '/roles'
            ],
            [
                'title' => $role->name,
                'href' => "/roles/$role->id"
            ],
            [
                'title' => 'Edit',
            ],
        ];

        return Inertia::render('Security/Roles/Edit', [
            'breadcrumbs' => $breadcrumbs,
            'role' => $role,
        ]);

    }

    public function update(Request $request, Role $role)
    {
        $input = $request->validate([
            'name' => "required|max:512|unique:roles,name,$role->id",
            'description' => 'required|max:1024',
        ]);

        $parameters = [
            'role' => $role->id,
            'errorBags' => [
                'default' => []
            ]
        ];

        $role->name = $input['name'];
        $role->description = $input['description'];
        $role->save();

        return to_route('roles.show', $parameters);
    }
}
