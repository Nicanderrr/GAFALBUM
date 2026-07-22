<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = \App\Models\User::where('is_admin', true)->latest()->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        // Add store logic later
    }

    public function edit($id)
    {
        // Add edit logic later
    }

    public function update(Request $request, $id)
    {
        // Add update logic later
    }

    public function destroy($id)
    {
        // Add destroy logic later
    }
}
