<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JwtAuth;
use App\Models\User;



class AuthController extends Controller
{
    protected $jwtAuth;

    public function __construct(JwtAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    /* ###################### LOGIN ####################### */

    public function login(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (empty($params_array)) {
            return $this->errorResponse(404, 'El usuario no se ha podido logear correctamente', 'El JSON no ha sido escrito correctamente');
        }

        $params_array = array_map('trim', $params_array);

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

    /* ###################### REGISTRER ################### */

    public function register(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (empty($params_array)) {
            return $this->errorResponse(403, 'El usuario no ha sido creado', 'El JSON no ha sido escrito correctamente');
        }

        $params_array = array_map('trim', $params_array);

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
