<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{

    
    /**
     * @OA\Post(
     *     path="/importFromJson",
     *     tags={"Administrador de encuestas"},
     *     summary="Import data from JSON - IMPORTA ENCUESTA DESDE JSON",
     *     description="Para importar el contenido de una encuesta desde un JSON convertido a string, para ello debe antes eliminar las referencia a imágenes, además debe está logueado como administrador",
     *     operationId="importFromJson",
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
     *                     example="{""title"":""Encuesta de prueba"",""description"":""Encuesta de prueba"",""pages"":[{""name"":""page1"",""elements"":[{""type"":""dropdown"",""name"":""question1"",""title"":""¿Cuál es tu color favorito?"",""choices"":[{""value"":""Rojo"",""text"":""Rojo""},{""value"":""Azul"",""text"":""Azul""},{""value"":""Verde"",""text"":""Verde""}]},{""type"":""text"",""name"":""question2"",""title"":""¿Cuál es tu correo electrónico?"",""inputType"":""email""},{""type"":""imagepicker"",""name"":""question3"",""title"":""¿Cuál es tu animal favorito?"",""choices"":[{""value"":""Perro"",""text"":""Perro""},{""value"":""Gato"",""text"":""Gato""},{""value"":""Pájaro"",""text"":""Pájaro""}]}]}]}",
     *                 ),
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
      
        $surveyData = json_decode($params_array['json'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Datos JSON inválidos'.json_last_error_msg()], 403);
        }
        $survey = Survey::where('title',$surveyData['title'])->first();
        if ($survey) {
            return response()->json(['message' => 'La encuesta ya existe'], 403);
        }

        // Crear la encuesta
        $survey = Survey::create([
            'title' => $surveyData['title'],
            'description' => $surveyData['description']
        ]);

        // Procesar cada página del JSON
        foreach ($surveyData['pages'] as $page) {
            $namepage = $page['name'];
            foreach ($page['elements'] as $element) {
                if ($element['type'] === 'dropdown') {
                    // Crear pregunta de opción única
                    $question = Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'name' => $element['name'],
                        'type' => 'single_option',
                        'page' => $namepage
                    ]);

                    // Insertar opciones de la pregunta
                    foreach ($element['choices'] as $choice) {
                        Option::create([
                            'question_id' => $question->id,
                            'text' => $choice['text'],
                            'page' => $namepage,
                            'name' => $element['name'],
                        ]);
                    }
                } elseif ($element['type'] === 'text' && $element['inputType'] === 'email') {
                    // Crear pregunta de texto
                    Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'type' => 'text',
                        'name' => $element['name'],
                        'page' => $namepage
                    ]);
                } elseif ($element['type'] === 'imagepicker') {
                    // Crear pregunta de opción múltiple (imágenes)
                    $question = Question::create([
                        'survey_id' => $survey->id,
                        'text' => $element['title'],
                        'name' => $element['name'],
                        'type' => 'multiple_option',
                        'page' => $namepage
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
                        'type' => 'date_of_birth',
                        'name' => $element['name'],
                        'page' => $namepage
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Encuesta importada exitosamente']);
    }
}
