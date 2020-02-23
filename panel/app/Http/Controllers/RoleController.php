<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\RoleRepository;

class RoleController extends Controller
{   
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function add(Request $request)
    {
        $rule = [
            'role' => 'required|string',
            'permission.*' => 'required|integer|exists:permissions,id'
        ];

        $v = \Validator::make($request->all(), $rule);

        if ($v->fails())
        {
            $response = [
                'success' => false, 
                'error' => $v->errors()
                ];
            
            return response()->json($response, 400);
        }

        try {
            $this->roleRepository->addRole($request->role, array_unique($request->permission));

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully added Role!!'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Adding Role: '.$e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request, $roleId)
    {
        $roleObj = \App\Roles::where('id', $roleId)->get()->first();

        if(!isset($roleObj)){  
            return response()->json([
                'success' => false,
                'error' => 'Role Does not exist!!!'
            ], 400);
        }

        $rule = [
            'role' => 'string|min:1',
            'permission.*' => 'integer|exists:permissions,id'
        ];

        $v = \Validator::make($request->all(), $rule);

        if ($v->fails())
        {
            $response = [
                'success' => false, 
                'error' => $v->errors()
                ];
            
            return response()->json($response, 400);
        }

        try {
            $this->roleRepository->editRole($request->role, array_unique($request->permission), $roleObj);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully Updated Role!!'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Updating Role: '.$e->getMessage()
            ], 500);
        }
    }

    public function remove(Request $request, $roleId)
    {
        $role = \App\Roles::where('id', $roleId)->get()->first();

        if(!isset($role)){  
            return response()->json([
                'success' => false,
                'error' => 'Role Does not exist!!!'
            ], 400);
        }

        try {
            $this->roleRepository->deleteRole($roleId);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully deleted Role!!'
                ]
            ], 200);

        } catch (\Exception $e) {
            //do some logging to avoid DB error in response
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Deleting Role'.$e->getMessage()
            ], 500);
        }
    }

    public function getRoleActions(Request $request)
    {   
        try {
            $userRoleId = \App\User::where('auth_token', $request->token)->get()->first()->role_id;

            $roleAllowedActions = $this->roleRepository->getRoleAllowedActions("role", $userRoleId);

            $response = ['success'=>true, 'data'=>$roleAllowedActions];
            return response()->json($response, 201);

        } catch (\Exception $e) {
            //do some logging also to know DB error if any
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while fetching Role Actions'
            ], 500);
        }
        
    }
}
