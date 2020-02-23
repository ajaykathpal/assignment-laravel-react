<?php
namespace App\Repositories;

class RoleRepository
{

    public function addRole($role, $permission = array())
    {
        \DB::transaction(function () use($role, $permission) {

            $roleId = \DB::table('roles')->insertGetId(['role' => $role]);
            
            $rolePermissionMappingData = array();

            foreach ($permission as $permissionId) {
                $rolePermissionMappingData[] = array('role_id' => $roleId, 'permission_id' => $permissionId);
            }
            \DB::table('role_permission_mappings')->insert($rolePermissionMappingData);
        });
    }

    public function editRole($roleName, $newPermission = array(), $roleObj)
    {
        \DB::transaction(function () use($roleName, $newPermission, $roleObj) {

            //check 
            if(isset($roleName)){
                \DB::table('roles')->where('id', $roleObj->id)->limit(1)->update(['role' => $roleName]);
            }

            //compare updated permission ids and add/remove changes

            //get existing permission ids
            $curPermissionData = \DB::table('role_permission_mappings')->select('permission_id')->where('role_id', $roleObj->id)->get();

            $removePermissionArr = array();

            foreach ($curPermissionData as $curPermission) {
                
                if(!in_array($curPermission->permission_id, $newPermission)){
                    $removePermissionArr[] = $curPermission->permission_id;
                }else{
                    if (($key = array_search($curPermission->permission_id, $newPermission)) !== false) {
                        unset($newPermission[$key]);
                    }
                }
            }

            // OPTIMIZE THIS
            //remove permissions
            foreach ($removePermissionArr as $curPermission) {
                \DB::table('role_permission_mappings')->where('role_id', $roleObj->id)->where('permission_id', $curPermission)->limit(1)->delete();
            }

            $rolePermissionMappingData = array();
            foreach ($newPermission as $permissionId) {
                $rolePermissionMappingData[] = array('role_id' => $roleObj->id, 'permission_id' => $permissionId);
            }
            if(count($rolePermissionMappingData) > 0){
                \DB::table('role_permission_mappings')->insert($rolePermissionMappingData);
            }
        });

        
    }

    public function deleteRole($roleId)
    {
        \DB::table('roles')->where('id', $roleId)->limit(1)->delete();
    }

    public function getRoleAllowedActions($page, $userRoleId){
        
        $userActionList = \DB::table('permissions')->select(['action'])
        ->join('role_permission_mappings', 'permissions.id', '=', 'role_permission_mappings.permission_id')
        ->where('permissions.page', $page)
        ->where('permissions.action', '!=', 'show')
        ->where('role_permission_mappings.role_id', $userRoleId)
        ->get();

        return $userActionList;
    }
}