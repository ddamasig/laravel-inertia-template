<?php

namespace App\Http\Controllers\Management\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Tenants\StoreTenantRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Admin\TenantService;
use App\Services\AuthService;
use App\Services\LocationService;
use App\Services\LogService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TenantController extends Controller
{

    protected LogService $logger;

    public function __construct()
    {
        $this->authorizeResource(Tenant::class, 'tenant');
        $this->logger = new LogService(Auth::user(), 'tenant-management');
    }

    public function index(Request $request): \Inertia\Response
    {
        $itemsPerPage = $request->get('per_page', 100);
        $breadcrumbs = [
            [
                'title' => 'Tenants',
                'href' => '/tenants'
            ]
        ];

        $resources = QueryBuilder::for(Tenant::class)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->where('name', 'ilike', "%$value%")
                        ->orWhere('domain', 'ilike', "%$value%");
                }),
                'status'
            ])
            ->allowedSorts([
                'name',
                'domain',
                'market_type',
                'email',
                'mobile_number',
                'status',
                'created_at'
            ])
            ->orderBy('name', 'asc')
            ->paginate($itemsPerPage);

        return Inertia::render('Management/Tenant/Index', [
            'breadcrumbs' => $breadcrumbs,
            'resources' => $resources
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Tenants',
                'href' => '/management/tenants'
            ],
            [
                'title' => 'Create',
            ],
        ];

        $roles = AuthService::getRoles();
        $permissions = AuthService::getPermissions();
        $provinces = LocationService::getProvinces();

        return Inertia::render('Management/Tenant/Create', [
            'breadcrumbs' => $breadcrumbs,
            'permissions' => $permissions,
            'provinces' => $provinces,
            'roles' => $roles,
        ]);
    }

    public function show(Tenant $tenant): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Tenants',
                'href' => '/management/tenants'
            ],
            [
                'title' => $tenant->name,
            ],
        ];

        $tenant = Tenant::with(['province', 'municipality'])
            ->find($tenant->id);
        $owner = $tenant->execute(function (Tenant $tenant) {
           return $tenant->name;
        });
        dd($owner);
        $roles = AuthService::getRoles(true);
        $permissions = AuthService::getPermissions();

        return Inertia::render('Management/Tenant/Show', [
            'breadcrumbs' => $breadcrumbs,
            'tenant' => $tenant,
            'permissions' => $permissions,
            'provinces' => LocationService::getProvinces(),
            'roles' => $roles,
        ]);
    }
//
//    public function edit(Request $request, User $user): \Inertia\Response
//    {
//        $breadcrumbs = [
//            [
//                'title' => 'Users',
//                'href' => '/users'
//            ],
//            [
//                'title' => $user->getFullNameAttribute(),
//                'href' => "/users/$user->id"
//            ],
//            [
//                'title' => 'Edit',
//            ],
//        ];
//
//        $user = User::with(['province', 'municipality'])
//            ->find($user->id);
//        $roles = AuthService::getRoles(true);
//        $permissions = AuthService::getPermissions();
//
//        return Inertia::render('Management/Security/Users/Edit', [
//            'breadcrumbs' => $breadcrumbs,
//            'user' => $user,
//            'permissions' => $permissions,
//            'provinces' => LocationService::getProvinces(),
//            'roles' => $roles,
//        ]);
//    }
//
    public function store(StoreTenantRequest $request): RedirectResponse
    {
        try {
            $tenant = TenantService::create($request->all());
        } catch (\Exception $exception) {
            $this->logger->activity('debug', 'Failed to create user.', [
                'exception' => $exception,
                'input' => $request->all(),
            ]);

            return redirect()->back()->withErrors([
                'custom' => 'Failed to create tenant.'
            ]);
        }
        return to_route('tenants.show', [
            'tenant' => $tenant
        ]);
    }
//
//    public function update(UpdateUserRequest $request, User $user): RedirectResponse
//    {
//        DB::beginTransaction();
//        try {
//            $user = UserService::updateModel($user, $request->all());
//            UserService::assignRole($user, $request->role_id);
//            if ($request->avatar) {
//                UserService::uploadAvatar($user, $request->avatar);
//            }
//            DB::commit();
//        } catch (\Exception $exception) {
//            DB::rollBack();
//
//            $this->logger->activity('debug', 'Failed to update user.', [
//                'exception' => $exception,
//                'input' => $request->all(),
//                'user_id' => $user->id,
//            ]);
//
//            return redirect()->back()->withErrors([
//                'custom' => 'Failed to update user.'
//            ]);
//        }
//
//        return to_route('users.show', [
//            'user' => $user->id,
//        ]);
//    }
//
//    public function destroy(Request $request, User $user): RedirectResponse
//    {
//        try {
//            $user->delete();
//            return redirect()->back();
//        } catch (\Exception $exception) {
//            $this->logger->activity('debug', 'Failed to delete user.', [
//                'exception' => $exception,
//                'input' => $request->all(),
//                'user_id' => $user->id,
//            ]);
//
//            return redirect()->back()->withErrors([
//                'custom' => 'Failed to delete user.'
//            ]);
//        }
//    }
}
