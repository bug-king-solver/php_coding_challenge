<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RandomUserController extends Controller
{
    public function index(Request $request)
    {
        // Validation rules
        $rules = [
            'limit' => 'required|integer|min:1|max:100',
            // Adjust the min and max values as needed
        ];

        // Custom error messages (optional)
        $messages = [
            'limit.required' => 'The limit field is required.',
            'limit.integer' => 'The limit field must be an integer.',
            'limit.min' => 'The limit field must be at least :min.',
            'limit.max' => 'The limit field may not be greater than :max.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Your existing code
        $limit = $request->limit;

        $response = Cache::remember("random_users_limit_$limit", 3600, function () use ($limit) {
            // Make requests to the randomuser.me API with the specified limit
            $users = [];
            for ($i = 0; $i < $limit; $i++) {
                $apiResponse = Http::get('https://randomuser.me/api/');
                $userData = $apiResponse->json()['results'][0];
                $users[] = [
                    'full_name' => $userData['name']['first'] . ' ' . $userData['name']['last'],
                    'phone' => $userData['phone'],
                    'email' => $userData['email'],
                    'country' => $userData['location']['country'],
                ];
            }

            // Sort users by last name in reverse alphabetical order
            usort($users, function ($a, $b) {
                return strcmp(strrev($a['full_name']), strrev($b['full_name']));
            });

            return $users;
        });

        // Convert the sorted user data to XML
        $xml = $this->arrayToXml($response);

        return Response::make($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function arrayToXml($data, $rootNodeName = 'data', $xml = null)
    {
        if ($xml === null) {
            $xml = new \SimpleXMLElement('<' . $rootNodeName . '/>');
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->arrayToXml($value, $key, $xml->addChild($key));
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
    }
}