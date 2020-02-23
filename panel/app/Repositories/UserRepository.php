<?php
namespace App\Repositories;

class UserRepository
{

    public function addUser($userData = array())
    {
        //get role id
        $roleId = \App\Roles::where('role', $userData['role'])->get()->first()->id;

        unset($userData['role']);

        if(!isset($roleId)){
            throw new Exception("Cannot get role id", 1);
        }

        $userData['role_id'] = $roleId;

        \DB::table('users')->insert($userData);

    }

    public function editUser($userData = array(), $userId)
    {
        \DB::table('users')->where('id', $userId)->limit(1)->update($userData);
    }

    public function deleteUser($userId)
    {
        \DB::table('users')->where('id', $userId)->limit(1)->delete();
    }

    public function getPageActionAccessData($action, $roleId) {

        $resData = array(
                    'profile'=> false,
                    'user'=> false,
                    'role'=> false
                );

        $pagesAllowedActionAccess = \DB::table('permissions')->select('page')
        ->join('role_permission_mappings', 'permissions.id', '=', 'role_permission_mappings.permission_id')
        ->where('permissions.action', $action)
        ->where('role_permission_mappings.role_id', $roleId)
        ->get();

        foreach ($pagesAllowedActionAccess as $curPage) {
            $resData[$curPage->page] = true;
        }

        return $resData;
    }

    public function getUserList(){
        $userList = \DB::table('users')->select(['users.id as id','name', 'email', 'dob', 'role'])
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->get();

        return $userList;
    }

    public function getUserAllowedActions($page, $userRoleId){

        $userActionList = \DB::table('permissions')->select(['action'])
        ->join('role_permission_mappings', 'permissions.id', '=', 'role_permission_mappings.permission_id')
        ->where('permissions.page', $page)
        ->where('permissions.action', '!=', 'show')
        ->where('role_permission_mappings.role_id', $userRoleId)
        ->get();

        return $userActionList;
    }
}