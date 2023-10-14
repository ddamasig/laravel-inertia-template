<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    protected function getUsers()
    {
        return User::select([
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

        $users = $this->getUsers();

        return Inertia::render('Security/Users/Create', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users
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
                'title' => $user->name,
            ],
        ];

        $users = $this->getUsers();
        $user = User::with('users')->find($user->id);

        return Inertia::render('Security/Users/Show', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users,
            'user' => $user,
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
        $users = $this->getUsers();

        return Inertia::render('Security/Users/Edit', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users,
            'user' => $user,
        ]);

    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'name' => 'required|max:512|unique:users,name',
            'description' => 'required|max:1024',
            'users' => 'required|array',
            'users.*' => 'required|exists:users,name',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $input['name'],
                'description' => $input['description'],
            ]);
            $user->syncUsers($input['users']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'custom' => $exception->getMessage()
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
