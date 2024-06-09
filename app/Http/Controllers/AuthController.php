<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JwtAuth;
use App\Models\User;

/**
 * @OA\Info(
 *     title="My Api documentation Att Edwin Mogollon",
 *     version="1.0.0"
 * )
 */


class AuthController extends Controller
{
    protected $jwtAuth;

    public function __construct(JwtAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

  
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"auth"},
     *     summary="Log in a user",
     *     description="This can only be done by the logged in user.",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         description="User log in",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "ser@prueba.com", "password": "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="token",
     *                     type="string"
     *                 ),
     *                 example={"token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid email/password supplied"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $params_array = $request->except('token');

        if( empty($params_array) )
            $params_array = $request->getContent();

        $validator = Validator::make($params_array, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(403, 'El usuario no se ha podido logear correctamente', $validator->errors());
        }

        $password = hash('sha256', $params_array['password']);
        $user = $this->jwtAuth->signup($params_array['email'], $password);

        if ($user['status'] != 'success') {
            return $this->errorResponse(403, 'El usuario no se ha podido logear correctamente', 'Los datos no han sido encontrados');
        }

        $data = [
            'status' => $user['status'],
            'code'   => 200,
            'msj'    => $user['msj'],
            'data'   => $user,
        ];

        return response()->json($data, $data['code']);
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"auth"},
     *     summary="Register a new user",
     *     description="This can only be done by the logged out user.",
     *     operationId="registerUser",
     *     @OA\RequestBody(
     *         description="User registration",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "user@prueba.com", "password": "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 ),
     *                 example={"message": "User registered successfully"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $params_array = $request->except('token');

        if( empty($params_array) )
            $params_array = $request->getContent();

        $validator = Validator::make($params_array, [
            'email'      => 'required|email|unique:users',
            'password'   => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(403, 'El usuario no ha sido creado', $validator->errors());
        }

        $password = hash('sha256', $params_array['password']);
        $user = new User([
            'email'      => $params_array['email'],
            'password'   => $password,
        ]);

        $user->save();

        $data = [
            'status' => 'success',
            'code'   => 200,
            'msj'    => 'El usuario ha sido creado',
        ];

        return response()->json($data, $data['code']);
    }

 

    protected function errorResponse($code, $message, $errors)
    {
        $data = [
            'status' => 'error',
            'code'   => $code,
            'msj'    => $message,
            'errors' => $errors,
        ];

        return response()->json($data, $code);
    }
}
