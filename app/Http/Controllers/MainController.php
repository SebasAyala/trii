<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public $url = 'https://rickandmortyapi.com/api/character';

    public function index ()
    {
        // Filtro que determina el módulo
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $gender = isset($_GET['gender']) ? $_GET['gender'] : null;

        $url = $this->url . ($name ? '?name=' . $name : '');
        $url = $url . ($status ? (str_contains($url, '?') ? '&' : '?') . 'status=' . $status : '');
        $url = $url . ($gender ? (str_contains($url, '?') ? '&' : '?') . 'gender=' . $gender : '');

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $res = json_decode($response->getBody());
        return response()->json(json_decode($response->getBody()), 200);
    }
}
