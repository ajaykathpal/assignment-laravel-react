<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use JWTAuthException;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }

    public function login(Request $request)
    {
        $user = \App\User::where('email', $request->email)->get()->first();
        if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
        {
            $token = self::getToken($request->email, $request->password);
            $user->auth_token = $token;
            $user->save();
            $response = [
                'success' => true, 
                'data' => [
                    'id'=>$user->id,
                    'auth_token'=>$user->auth_token,
                    'name'=>$user->name, 
                    'email'=>$user->email,
                    'role' => \App\Roles::where('id', $user->role_id)->get()->first()->role,
                    'tabs_show' => $this->userRepository->getPageActionAccessData('show', $user->role_id)
                    ]
                ];           
        }
        else 
          $response = ['success'=>false, 'data'=>'Record doesnt exists'];
      

        return response()->json($response, 201);
    }

    public function add(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'dob' => 'required|date_format:Y-m-d|before:today',
            'role' => 'required|exists:roles,role'
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

        $userData = [

            'name' => $request->name,
            'email' => $request->email,
            'auth_token' => '',
            'dob' => date('Y-m-d H:i:s', strtotime($request->dob)),
            'password' => \Hash::make($request->password),
            'role' => $request->role,
        ];
        
        try {
            $this->userRepository->addUser($userData);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully added User!!'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Creating User: '.$e->getMessage()
            ], 500);
        }
        
    }

    public function edit(Request $request, $userId)
    {   
        $user = \App\User::where('id', $userId)->get()->first();

        if(!isset($user)){  
            return response()->json([
                'success' => false,
                'error' => 'User Does not exist!!!'
            ], 400);
        }

        $rule = [
            'name' => 'string',
            'email' => 'email',
            'password' => 'string',
            'dob' => 'date_format:Y-m-d|before:today',
            'role' => 'exists:roles,role'
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

        $userData = array();
        
        $userData['name'] = isset($request->name) ? $request->name : '';
        $userData['email'] = isset($request->email) ? $request->email : '';
        $userData['dob'] = isset($request->dob) ? date('Y-m-d H:i:s', strtotime($request->dob)) : '';
        $userData['password'] = isset($request->password) ? \Hash::make($request->password) : '';
        $userData['role_id'] = isset($request->role) ? \App\Roles::where('role', $request->role)->get()->first()->id : '';

        $userData = array_filter($userData);

        if(count($userData) < 1){
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Nothing to update!!'
                ]
            ], 200);
        }

        try {
            $this->userRepository->editUser($userData, $userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully updated User!!'
                ]
            ], 200);

        } catch (\Exception $e) {
            //do some logging also to know DB error if any
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Updating User'
            ], 500);
        }
    }

    public function remove(Request $request, $userId)
    {
        $user = \App\User::where('id', $userId)->get()->first();

        if(!isset($user)){  
            return response()->json([
                'success' => false,
                'error' => 'User Does not exist!!!'
            ], 400);
        }

        try {
            $this->userRepository->deleteUser($userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Successfully deleted User!!'
                ]
            ], 200);

        } catch (\Exception $e) {
            //do some logging also to know DB error if any
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Deleting User'
            ], 500);
        }
    }

    public function getList(Request $request)
    {   
        try {

            $userList = $this->userRepository->getUserList();

            $response = ['success'=>true, 'data'=>$userList];
            return response()->json($response, 201);

        } catch (\Exception $e) {
            //do some logging also to know DB error if any
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while Deleting User'
            ], 500);
        }
    }

    public function getUserActions(Request $request)
    {   
        try {
            $userRoleId = \App\User::where('auth_token', $request->token)->get()->first()->role_id;

            $userAllowedActions = $this->userRepository->getUserAllowedActions("user", $userRoleId);

            $response = ['success'=>true, 'data'=>$userAllowedActions];
            return response()->json($response, 201);

        } catch (\Exception $e) {
            //do some logging also to know DB error if any
            return response()->json([
                'success' => false,
                'error' => 'Something Went Wrong while fetching User Actions'
            ], 500);
        }
        
    }
}
