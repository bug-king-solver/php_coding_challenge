<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class RandomUserController extends Controller
{
    public function index()
    {
        $response = Cache::remember('random_users', 3600, function () {
            // Make 10 requests to the randomuser.me API
            $users = [];
            for ($i = 0; $i < 10; $i++) {
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

        $xml = $this->arrayToXml($response);
        return Response::make($xml, '200')
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