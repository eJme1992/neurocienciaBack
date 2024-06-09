<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */


class SurveyController extends Controller
{

     /**
     * @OA\Post(
     *     path="/importFromJson",
     *     tags={"survey"},
     *     summary="Import data from JSON",
     *     description="This can only be done by the logged in user.",
     *     operationId="importFromJson",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="Authorization",
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
     *                     type="string"
     *                 ),
     *                 example={"json": "your JSON data here"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data imported successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid JSON data supplied"
     *     )
     * )
     */
    public function importFromJson(Request $request)
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

        // Decodificar JSON
        $surveyData = json_decode($params_array['json'], true);
        
        // Crear la encuesta
        $survey = Survey::create([
            'title' => $surveyData['title'],
            'description' => $surveyData['description']
        ]);

        // Procesar cada página del JSON
        foreach ($surveyData['pages'] as $page) {
            foreach ($page['elements'] as $element) {
                if ($element['type'] === 'dropdown') {
                    // Crear pregunta de opción única
                    $question = Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'type' => 'single_option'
                    ]);

                    // Insertar opciones de la pregunta
                    foreach ($element['choices'] as $choice) {
                        Option::create([
                            'question_id' => $question->id,
                            'text' => $choice['text']
                        ]);
                    }
                } elseif ($element['type'] === 'text' && $element['inputType'] === 'email') {
                    // Crear pregunta de texto
                    Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'type' => 'text'
                    ]);
                } elseif ($element['type'] === 'imagepicker') {
                    // Crear pregunta de opción múltiple (imágenes)
                    $question = Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'type' => 'multiple_option'
                    ]);

                    // Insertar opciones de la pregunta
                    foreach ($element['choices'] as $choice) {
                        Option::create([
                            'question_id' => $question->id,
                            'text' => $choice['value']
                        ]);
                    }
                } elseif ($element['type'] === 'text' && $element['inputType'] === 'datetime-local') {
                    // Crear pregunta de fecha de nacimiento
                    Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'type' => 'date_of_birth'
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Encuesta importada exitosamente']);
    }
}