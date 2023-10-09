<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
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
        return Inertia::render('Security/Permissions', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
