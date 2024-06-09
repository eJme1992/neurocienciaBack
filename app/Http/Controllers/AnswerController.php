<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function TimeStore(Request $request)
    {
        $params_array = $request->except('token');

        if( empty($params_array) )
            $params_array = $request->getContent();

        $validator = Validator::make($params_array, [
            'email'      => 'required|email',
            'survey'     => 'required'
        ]);

        if ($validator->fails()) {
            return json_encode(['message' => 'No se han llenado los datos correctamente'], 403);
        }
        $user = $request->header('user');
        dd('user');
        $survey = Survey::where('title', $params_array['survey'])->first();

    }
}
