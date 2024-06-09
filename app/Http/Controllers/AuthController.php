<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JwtAuth;
use App\Models\User;
use App\Models\UserAnswer;

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

        if (empty($user)) {
            return $this->errorResponse(403, 'El usuario no se ha podido logear correctamente', 'Los datos no han sido encontrados');
        }

        $data = [
            'status' => 'success', 
            'code'   => 200,
            'msj'    => 'El usuario se ha logeado correctamente',
            'data'   => $user,
        ];

        return response()->json($data, 200);
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
            'name'       => $params_array['email'],
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


        /**
     * @OA\Post(
     *     path="/begin",
     *     summary="Registra una respuesta",
     *     description="Este método registra una respuesta del usuario",
     *     tags={"Respuestas"},
     *     @OA\RequestBody(
     *         description="Datos necesarios para registrar una respuesta",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="El correo electrónico del usuario",
     *                 example="user@example.com"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta registrada exitosamente",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al registrar la respuesta",
     *     ),
     * )
     */
    public function registerAnsawer(Request $request)
    {
        $params_array = $request->except('token');

        if( empty($params_array) )
            $params_array = $request->getContent();

        $validator = Validator::make($params_array, [
            'email'      => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(403, 'El usuario no ha sido creado', $validator->errors());
        }

        $user = UserAnswer::where('email', $params_array['email'])->first();

        if(!empty($user)) {
            return $this->loginUserAnswer($params_array['email']);
        }

        $user = new UserAnswer([
            'email'      => $params_array['email'],
        ]);

        $user->save();

        return $this->loginUserAnswer($params_array['email']);
    }

    private function loginUserAnswer(string $email){
        $user = $this->jwtAuth->signupAnswer($email);
        $data = [
            'status' => 'success', 
            'code'   => 200,
            'msj'    => 'El usuario se ha logeado correctamente',
            'data'   => $user,
        ];
        return response()->json($data, 200);
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
