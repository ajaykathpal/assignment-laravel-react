<?php

namespace App\Http\Middleware;

use Closure;

class CanManageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->input('token');
            $user = \App\User::where('auth_token', $token)->get()->first();

            if(!isset($user)){
                throw new \Exception("Error fetching User !!!", 1);
            }
            $pathArr = explode("/", trim($request->getPathInfo()));

            $page = $pathArr[2];
            $action = $this->getActionFromMethod($request->method());
            
            if(!$this->canUserManageRequest($user, $page, $action)){
                return response()->json([
                        'success'=>false,
                        'error'=>'You do not have access to perform this action.'
                    ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                        'success'=>false,
                        'error'=>'Something went wrong while checking user permissions: '.$e->getMessage()
                        ], 500);
        }

        return $next($request);
    }

    private function getActionFromMethod($method) {

        $res = 'add';

        switch ($method) {
            case 'PATCH':
                $res = 'edit';
                break;

            case 'DELETE':
                $res = 'delete';
                break;

            case 'POST':
                $res = 'add';
                break;

            default:
            $res = 'show';
                break;
        }

        return $res;
    }

    private function canUserManageRequest($user, $page, $action) {
        $response = true;

        $res = \DB::table('permissions')
            ->join('role_permission_mappings', 'permissions.id', '=', 'role_permission_mappings.permission_id')
            ->where('permissions.page', '=', $page)
            ->where('permissions.action', '=', $action)
            ->where('role_permission_mappings.role_id', '=', $user->role_id)->count();

        if($res < 1)
            $response = false;

        return $response;
    }
}
