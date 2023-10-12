<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    protected function getPermissions()
    {
        return Permission::select([
            'id',
            'guard_name',
            'name',
            'description'
        ])->orderBy('name', 'asc')->get();
    }

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

        $permissions = $this->getPermissions();

        return Inertia::render('Security/Roles/Create', [
            'breadcrumbs' => $breadcrumbs,
            'permissions' => $permissions
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

        $permissions = $this->getPermissions();
        $role = Role::with('permissions')->find($role->id);

        return Inertia::render('Security/Roles/Show', [
            'breadcrumbs' => $breadcrumbs,
            'permissions' => $permissions,
            'role' => $role,
        ]);
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

        $role = Role::with('permissions')->find($role->id);
        $permissions = $this->getPermissions();

        return Inertia::render('Security/Roles/Edit', [
            'breadcrumbs' => $breadcrumbs,
            'permissions' => $permissions,
            'role' => $role,
        ]);

    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'name' => 'required|max:512|unique:roles,name',
            'description' => 'required|max:1024',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $input['name'],
                'description' => $input['description'],
            ]);
            $role->syncPermissions($input['permissions']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'custom' => $exception->getMessage()
            ]);
        }
        return to_route('roles.index');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $input = $request->validate([
            'name' => "required|max:512|unique:roles,name,$role->id",
            'description' => 'required|max:1024',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role->name = $input['name'];
            $role->description = $input['description'];
            $role->save();

            $role->syncPermissions($input['permissions']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

        return to_route('roles.show', [
            'role' => $role->id,
        ]);
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->back();
    }
}
