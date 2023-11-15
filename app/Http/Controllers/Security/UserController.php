<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Users\StoreUserRequest;
use App\Http\Requests\Management\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\LocationService;
use App\Services\LogService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{

    protected LogService $logger;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
        $this->logger = new LogService(Auth::user(), 'user-management');
    }

    public function index(Request $request): \Inertia\Response
    {
        $itemsPerPage = $request->get('per_page', 100);
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/management/users'
            ]
        ];

        $resources = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->where('first_name', 'ilike', "%$value%")
                        ->orWhere('last_name', 'ilike', "%$value%");
                }),
                'status'
            ])
            ->allowedSorts([
                AllowedSort::field('full_name', 'first_name'),
                'email',
                'mobile_number',
                'status',
                'created_at'
            ])
            ->orderBy('first_name', 'asc')
            ->paginate($itemsPerPage);

        return Inertia::render('Management/Security/Users/Index', [
            'breadcrumbs' => $breadcrumbs,
            'resources' => $resources
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/management/users'
            ],
            [
                'title' => 'Create',
            ],
        ];

        $roles = AuthService::getRoles();
        $permissions = AuthService::getPermissions();
        $provinces = LocationService::getProvinces();

        return Inertia::render('Management/Security/Users/Create', [
            'breadcrumbs' => $breadcrumbs,
            'permissions' => $permissions,
            'provinces' => $provinces,
            'roles' => $roles,
        ]);
    }

    public function show(User $user): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/management/users'
            ],
            [
                'title' => $user->getFullNameAttribute(),
            ],
        ];

        $user = User::with(['province', 'municipality'])
            ->find($user->id);
        $roles = AuthService::getRoles(true);
        $permissions = AuthService::getPermissions();

        return Inertia::render('Management/Security/Users/Show', [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'permissions' => $permissions,
            'provinces' => LocationService::getProvinces(),
            'roles' => $roles,
        ]);
    }

    public function edit(Request $request, User $user): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/management/users'
            ],
            [
                'title' => $user->getFullNameAttribute(),
                'href' => "/management/users/$user->id"
            ],
            [
                'title' => 'Edit',
            ],
        ];

        $user = User::with(['province', 'municipality'])
            ->find($user->id);
        $roles = AuthService::getRoles(true);
        $permissions = AuthService::getPermissions();

        return Inertia::render('Management/Security/Users/Edit', [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'permissions' => $permissions,
            'provinces' => LocationService::getProvinces(),
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            UserService::create($request->all());
        } catch (\Exception $exception) {
            $this->logger->activity('debug', 'Failed to create user.', [
                'exception' => $exception,
                'input' => $request->all(),
            ]);

            return redirect()->back()->withErrors([
                'custom' => 'Failed to create user.'
            ]);
        }
        return to_route('users.index');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = UserService::updateModel($user, $request->all());
            UserService::assignRole($user, $request->role_id);
            if ($request->avatar) {
                UserService::uploadAvatar($user, $request->avatar);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            $this->logger->activity('debug', 'Failed to update user.', [
                'exception' => $exception,
                'input' => $request->all(),
                'user_id' => $user->id,
            ]);

            return redirect()->back()->withErrors([
                'custom' => 'Failed to update user.'
            ]);
        }

        return to_route('users.show', [
            'user' => $user->id,
        ]);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        try {
            $user->delete();
            return redirect()->back();
        } catch (\Exception $exception) {
            $this->logger->activity('debug', 'Failed to delete user.', [
                'exception' => $exception,
                'input' => $request->all(),
                'user_id' => $user->id,
            ]);

            return redirect()->back()->withErrors([
                'custom' => 'Failed to delete user.'
            ]);
        }
    }
}
