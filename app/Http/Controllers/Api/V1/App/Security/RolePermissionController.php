<?php

namespace App\Http\Controllers\Api\V1\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{
    // Get all permissions
    public function getAllPermission(Request $request)
    {
        $permissionGroups = PermissionGroup::orderBy('display_order', 'asc')->get();
        $permissions = [];
        foreach($permissionGroups as $permissionGroup){
            $data = Permission::active()
                                ->notDelete()
                                ->where('permission_group_id', $permissionGroup->id)
                                ->where('developer_only', false)
                                ->select('id', 'module', 'permission_name', 'permission_slug')
                                ->orderBy('display_order', 'asc')
                                ->orderBy('sub_display_order', 'asc')
                                ->get()->groupBy('module');
            $subPermissions = [];        
            foreach($data as $key => $items){
                $subPermissions [] = [
                    'module' => $key,
                    'permission_name' => $key,
                    'permission_slug' => $key,
                    'permissions' => $items
                ];
            }
            $permissions [] = [
                'module' => $permissionGroup->name,
                'permission_name' => $permissionGroup->name,
                'permission_slug' => $permissionGroup->name,
                'permissions' => $subPermissions
            ];
        }
        
        return response()->json($permissions);
    }

    // Get permission by role
    public function getPermissionByRole($roleId){
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => "Role not found."
            ]);
        }

        $permissions = [];
        $rolePermissions = RolePermission::where('role_id',  $roleId)->orderBy('created_at', 'desc')->get();
        if(count($rolePermissions) > 0){
            foreach ($rolePermissions as $rolePermission){
        
                $permissions[] = [
                    'id' => $rolePermission->permission->id,
                    'module' => $rolePermission->permission->module,
                    'permission_name' => $rolePermission->permission->permission_name,
                    'permission_slug' => $rolePermission->permission->permission_slug,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    // Update role permission
    public function updateRolePermission(Request $request){
        $permissionIds = $request->permission_ids;
        RolePermission::where('role_id',  $request->role_id)->delete();
        
        $rolePermission = [];
        foreach($permissionIds as $permissionId){
            $rolePermission[] = [
                'id' => Str::uuid()->toString(),
                'role_id' => $request->role_id,
                'permission_id' => $permissionId
            ];
        }

        // dd($rolePermission);

        RolePermission::insert($rolePermission);

        return response()->json([
            'success' => true,
            'message' => "Role permission have been updated.",
            'data' => $this->getPermissionByRole($request->role_id)->getData(true)
        ]);
    }
}
