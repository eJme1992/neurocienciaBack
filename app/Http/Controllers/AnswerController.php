<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function __construct()
    {
        // Allow CORS Se le agrega esto para o bligarlos a correr todo en un servidor o forzarles un url
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    /**
     * @OA\Post(
     *     path="/registerAnswer",
     *     tags={"Respuestas"},
     *     summary="Registro final de las respuestas",
     *     description="Registra las respuestas de un cliente",
     *     operationId="registerAnswer",
     *     @OA\Parameter(
     *         name="Authorization_",
     *         in="header",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="Bearer token for authorization"
     *     ),
     *     @OA\RequestBody(
     *         description="JSON data to import",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="json",
     *                     type="string",
     *                     example="{""name"":""page1"",""title"":""Email"",""time"":{""minutes"":""00"",""seconds"":""04"",""milliseconds"":""580""},""value"":""ss@mail.com""},{""name"":""page2"",""title"":""Genero"",""time"":{""minutes"":""00"",""seconds"":""03"",""milliseconds"":""050""},""value"":""Item2""},{""name"":""page3"",""title"":""Edad"",""time"":{""minutes"":""00"",""seconds"":""06"",""milliseconds"":""630""},""value"":""2024-06-09T22:05""},{""name"":""page4"",""title"":""Preferencia"",""time"":{""minutes"":""00"",""seconds"":""02"",""milliseconds"":""220""},""value"":""Image1""}"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta almacenada correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid JSON data supplied"
     *     )
     * )
     */
    public function answersUpdate(Request $request)
    {
        try {
            $params_array = $request->all();

            $validator = Validator::make($params_array, [
                'page' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Datos JSON inválidos'], 403);
            }

            $data = json_decode($params_array['json'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['message' => 'Datos JSON inválidos: ' . json_last_error_msg()], 403);
            }

            $clientToken = json_decode($request->header('client'));
            $client = UserAnswer::where('email', $clientToken->email)->first();
            $survey = Survey::where('title', $clientToken->survey)->first();

            if (!$client || !$survey) {
                return response()->json(['message' => 'No se han encontrado los datos'], 403);
            }

            foreach ($data as $item) {
                $answer = new Answer;   
                $survey = Question::where('page', $item['name'])->first();
                $answer->answer      =  $item['value'];
                $answer->time_spent =  $item['time']['minutes'] . ":" . $item['time']['seconds'] . ":" . $item['time']['milliseconds'];
                $answer->save();
            }

            return response()->json(['message' => 'Respuesta almacenada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() . $e->getLine()], 403);
        }
    }
}
