<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use ZipArchive;

class MainController extends Controller
{
    public $url = 'https://rickandmortyapi.com/api/character';

    public function index ()
    {
        // Filtros
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $gender = isset($_GET['gender']) ? $_GET['gender'] : null;
        $zip = isset($_GET['zip']) ? $_GET['zip'] : false;

        // Genración de URL
        $url = $this->url . ($name ? '?name=' . $name : '');
        $url = $url . ($status ? (str_contains($url, '?') ? '&' : '?') . 'status=' . $status : '');
        $url = $url . ($gender ? (str_contains($url, '?') ? '&' : '?') . 'gender=' . $gender : '');

        // Consumo del API y respuesta
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        if ($zip) {
            // Creación del archivo
            $zip_file = $zip . '.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $zip->addFromString('data.json', $response->getBody());
            $zip->close();
    
            return response()->download($zip_file);
        } else {
            return response()->json(json_decode($response->getBody()), 200);
        }
    }
}
