<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\Yaml\Yaml;

class SwaggerController extends Controller
{
    public function swaggerUI()
    {
        return view('swagger.index');
    }

    public function swaggerSpec()
    {
        $yaml = file_get_contents(public_path('swagger.yaml'));
        return Response::make($yaml, 200, ['Content-Type' => 'application/yaml']);
    }

    public function swaggerJson()
    {
        $yaml = file_get_contents(public_path('swagger.yaml'));
        
        try {
            if (function_exists('yaml_parse')) {
                $json = yaml_parse($yaml);
            } else {
                $json = Yaml::parse($yaml);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to parse Swagger specification'], 500);
        }
        
        return response()->json($json);
    }
}
