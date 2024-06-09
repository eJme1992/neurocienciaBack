<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\UserAnswer;

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
     *    @OA\RequestBody(
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
     *                     property="survey",
     *                     type="string",
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
     *             @OA\Property(property="message", type="string", example="Hora almacenada correctamente"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al almacenar la hora"),
     *         )
     *     ),
     * )
     */
    public function TimeStore(Request $request)
    {
        $params_array = $request->except('token');

        if( empty($params_array) )
            $params_array = $request->getContent();

        $validator = Validator::make($params_array, [
            'page'       => 'required',
            'time'     => 'required'
        ]);
        if ($validator->fails()) {
            return json_encode(['message' => 'No se han llenado los datos correctamente'], 403);
        }
        $clientToken = json_decode($request->header('client'));
        $client = UserAnswer::where('email', $clientToken->email)->first();
        $survey = Survey::where('title', $clientToken->survey)->first();
        $question = $survey->questions()->where('page', $params_array['page'])->first();

        if( !$client || !$survey || !$question )
            return json_encode(['message' => 'No se han encontrado los datos'], 403);
        
        // Registra el tiempo
        Answer::create([
            'question_id' => $question->id,
            'survey_id'   => $survey->id,
            'user_id'     => $client->id,
            'time_spent'  => $params_array['time']
        ]);

        return json_encode(['message' => 'Hora almacenada correctamente'], 200);
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
     *                     example="{""question1"":""edwin@gmail.com"",""question2"":""Item 1"",""question3"":""2024-06-21T15:39"",""question4"":""Image 2""}",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta almacenada correctamente",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid JSON data supplied"
     *     )
     * )
     */
    public function AnwersUpdate(Request $request)
    {
        $params_array = $request->except('token');

        if( empty($params_array) )
            $params_array = $request->getContent();

        $validator = Validator::make($params_array, [
            'json'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Datos JSON inválidos'], 403);
        }
      
        $date = json_decode($params_array['json'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Datos JSON inválidos'.json_last_error_msg()], 403);
        }
       /*
             array:4 [ // app/Http/Controllers/AnswerController.php:160
            "question1" => "edwin@gmail.com"
            "question2" => "Item 1"
            "question3" => "2024-06-21T15:39"
            "question4" => "Image 2"
            ]
       */

        $clientToken = json_decode($request->header('client'));
        $client = UserAnswer::where('email', $clientToken->email)->first();
        $survey = Survey::where('title', $clientToken->survey)->first();
        dd($clientToken->survey);
        foreach ($date as $key => $value) {
            $questions = Question::where('name', $key)->where('survey_id', '=',$survey->id)->first();
            // Registra la respuesta
            if($questions->type !== 'dropdown'){
            Answer::where('question_id', $questions->id)
                ->where('survey_id', $survey->id)
                ->where('user_id', $client->id)
                ->update(['answer' => $value]);
            }
            if($questions->type == 'dropdown'){
                $option = $questions->options()->where('text', $value)->first();
                Answer::where('question_id', $questions->id)
                    ->where('survey_id', $survey->id)
                    ->where('user_id', $client->id)
                    ->update(['option_id' => $option->id]);
            }
        }
        
        return json_encode(['message' => 'Respuesta almacenada correctamente'], 200);
    }
}
