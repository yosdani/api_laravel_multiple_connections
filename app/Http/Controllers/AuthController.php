<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
//    public function __construct() {
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
//    }


    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response(['status' => 'success', 'data' => $user], 200);
    }
    /**
     * Login the user
     * @param Request $request
     * @return JsonResponse
     ** @OA\Post (
     *      path="/authenticate",
     *      tags={"Auth"},
     *      summary="Login user",
     *      description="Login the user",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                  @OA\Property(
     *                     property="email",
     *                     description="Email of user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password of user",
     *                     type="string"
     *                 ),
     *             )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *       @OA\Response(
     *          response=404,
     *          description="Email or password incorrect"
     *      )
     * )
     */
    public function authenticate(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials))
        {
            return response(['status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'],
                400);
        }

//        return response([
//            'status' => 'success',
//            'token' => $token
//        ]);
        return $this->createNewToken($token);

//        $user = User::where('email', $credentials['email'])
//            ->select('id','name','email')
//            ->first();
//
//        $role = Role::find($user->id);
//
//        $user->role = $role->name;
//        echo($user->role);
//        return $this->response->array(compact('token','user'))->setStatusCode(200);

    }

    public function user(Request $request)
    {

        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Log out
     *   Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */

    public function logout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        try {
            JWTAuth::invalidate($request->input('token'));
            return response([
                'status' => 'success',
                'msg' => 'You have successfully logged out.'
            ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token

            return response([
                'status' => 'error',
                'msg' => 'Failed to logout, please try again.'
            ]);
        }
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    public function getToken(){
        if ( ! $user = JWTAuth::parseToken()->authenticate() ) {
            return response()->json(['User Not Found'], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        return response()->json(['email' => $user->email, 'token' => $newToken], 200);
    }





}