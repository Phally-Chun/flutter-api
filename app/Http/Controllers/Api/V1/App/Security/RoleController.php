<?php

namespace App\Http\Controllers\Api\V1\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Http\Resources\Api\V1\Admin\Security\RoleResource;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Admin\Security\RoleRequest;

class RoleController extends Controller
{
    // Get role with pagination
    public function index(Request $request)
    {
        $per_page = 15;
        if($request->per_page) {
            $per_page = $request->per_page > 500 ? 500 : $request->per_page;
        }

        if($request->search === 'null'){
            $request['search'] = null;
        }

        $data = Role::where(function ($q) use ($request) {
            if($request->search) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                 ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            }

            if ($request->name) {
                $q->where('name', 'like', '%' . $request->name . '%');
            }

            if($request->status){
                $q->where('status', $request->status);
            }
        })
        ->with('permissions')
        ->exceptRoot()
        ->notDelete()->orderBy('created_at', 'desc')
        ->paginate($per_page);

        $resource = RoleResource::collection($data)->response()->getData(true);

        return response()->json([
            'data' => $resource['data'],
            'meta' => [
                'current_page' => $resource['meta']['current_page'],
                'last_page' => $resource['meta']['last_page'],
                'total' => $resource['meta']['total'],
            ]
        ]);
    }

    // Create new role
    public function store(RoleRequest $request)
    {
        Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "Role have been created."
        ]);
    }

    // Get role by id
    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => "Role not found."
            ]);
        }

        return response()->json([
            'role' => new RoleResource($role)
        ]);
    }

    // Update role by id
    public function update(RoleRequest $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => "Role not found."
            ]);
        }

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'role' => new RoleResource($role),
            'message' => "Role have been updated."
        ]);
    }

    // Delete role
    public function destroy($id)
    {

        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => "User not found."
            ]);
        }

        $role->update([
            'status' => 3,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Role have been deleted."
        ]);
    }

    // get active role
    public function getActiveRole(Request $request)
    {
        $per_page = 15;
        if($request->per_page) {
            $per_page = $request->per_page > 500 ? 500 : $request->per_page;
        }

        $data = Role::where(function ($q) use ($request) {
            if($request->search) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                 ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            }
        })
        ->exceptRoot()
        ->active()
        ->notDelete()->orderBy('created_at', 'desc')
        ->paginate($per_page);

        $resource = RoleResource::collection($data)->response()->getData(true);

        return response()->json([
            'data' => $resource['data'],
            'meta' => [
                'current_page' => $resource['meta']['current_page'],
                'last_page' => $resource['meta']['last_page'],
                'total' => $resource['meta']['total'],
            ]
        ]);
    }
}
