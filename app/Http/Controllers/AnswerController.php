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
    /**
     * @OA\Post(
     *     path="/timeStore",
     *     summary="Almacena el tiempo por pagina de un cliente",
     *     tags={"Respuestas"},
     *     description="Almacena el tiempo por pagina de un cliente",
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
     *         description="Cliente login",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="page",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="time",
     *                     type="string"
     *                 ),
     *                 example={"time": "234", "page": "page1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Hora almacenada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al almacenar la hora")
     *         )
     *     )
     * )
     */
    public function timeStore(Request $request)
    {
        try {
            $params_array = $request->all();

            $validator = Validator::make($params_array, [
                'page' => 'required|string',
                'time' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'No se han llenado los datos correctamente'], 403);
            }

            $clientToken = json_decode($request->header('client'));
            $client = UserAnswer::where('email', $clientToken->email)->first();
            $survey = Survey::where('title', $clientToken->survey)->first();
            $question = $survey->questions()->where('page', $params_array['page'])->first();

            if (!$client || !$survey || !$question) {
                return response()->json(['message' => 'No se han encontrado los datos'], 403);
            }

            // Registra el tiempo
            Answer::create([
                'question_id' => $question->id,
                'survey_id' => $survey->id,
                'user_id' => $client->id,
                'time_spent' => $params_array['time']
            ]);

            return response()->json(['message' => 'Hora almacenada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() . $e->getLine()], 403);
        }
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
     *                     example="{""question1"":""edwin@gmail.com"",""question2"":""Item 1"",""question3"":""2024-06-21T15:39"",""question4"":""Image 2""}"
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
                'json' => 'required'
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

            foreach ($data as $key => $value) {
                $question = Question::where('name', $key)->where('survey_id', $survey->id)->first();

                if (!$question) {
                    return response()->json(['message' => 'Pregunta no encontrada: ' . $key], 403);
                }

                if ($question->type !== 'dropdown') {
                    Answer::updateOrCreate(
                        [
                            'question_id' => $question->id,
                            'survey_id' => $survey->id,
                            'user_id' => $client->id
                        ],
                        ['answer' => $value]
                    );
                } else {
                    $option = $question->options()->where('text', $value)->first();
                    if ($option) {
                        Answer::updateOrCreate(
                            [
                                'question_id' => $question->id,
                                'survey_id' => $survey->id,
                                'user_id' => $client->id
                            ],
                            ['option_id' => $option->id]
                        );
                    }
                }
            }

            return response()->json(['message' => 'Respuesta almacenada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() . $e->getLine()], 403);
        }
    }
}
