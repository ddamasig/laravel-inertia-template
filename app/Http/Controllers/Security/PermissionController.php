<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
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
                'href' => '/permissions'
            ],
            [
                'title' => 'View Users',
                'href' => '/permissions/1'
            ],
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

        return Inertia::render('Security/Permissions', [
            'breadcrumbs' => $breadcrumbs,
            'paginated' => $resources
        ]);
    }
}
