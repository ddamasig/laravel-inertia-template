<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\LocationService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): \Inertia\Response
    {
        $itemsPerPage = $request->get('per_page', 100);
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/users'
            ]
        ];

        $resources = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->where('first_name', 'ilike', "%$value%")
                        ->orWhere('last_name', 'ilike', "%$value%");
                }),
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

        return Inertia::render('Security/Users/Index', [
            'breadcrumbs' => $breadcrumbs,
            'resources' => $resources
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/users'
            ],
            [
                'title' => 'Create',
            ],
        ];

        $roles = AuthService::getRoles();
        $permissions = AuthService::getPermissions();
        $provinces = LocationService::getProvinces();

        return Inertia::render('Security/Users/Create', [
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
                'href' => '/users'
            ],
            [
                'title' => $user->getFullNameAttribute(),
            ],
        ];

        $roles = AuthService::getRoles();
        $permissions = AuthService::getPermissions();
        $user = User::with('users')->find($user->id);

        return Inertia::render('Security/Users/Show', [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    public function edit(Request $request, User $user): \Inertia\Response
    {
        $breadcrumbs = [
            [
                'title' => 'Users',
                'href' => '/users'
            ],
            [
                'title' => $user->name,
                'href' => "/users/$user->id"
            ],
            [
                'title' => 'Edit',
            ],
        ];

        $user = User::with('users')->find($user->id);
        $permissions = AuthService::getPermissions();
        $roles = AuthService::getRoles();
        $user = User::with('users')->find($user->id);

        return Inertia::render('Security/Users/Edit', [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
            'permissions' => $permissions,
            'roles' => $roles,
        ]);

    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $user = UserService::createModel($request->all());
            if ($request->avatar) {
                UserService::uploadAvatar($user, $request->avatar);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'custom' => 'Failed to create user.'
            ]);
        }
        return to_route('users.index');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $input = $request->validate([
            'name' => "required|max:512|unique:users,name,$user->id",
            'description' => 'required|max:1024',
            'users' => 'required|array',
            'users.*' => 'required|exists:users,name',
        ]);

        DB::beginTransaction();
        try {
            $user->nam = $input['name'];
            $user->description = $input['description'];
            $user->save();

            $user->syncUsers($input['users']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return to_route('users.show', [
            'user' => $user->id,
        ]);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->back();
    }
}
