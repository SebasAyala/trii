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

        // Genración de URL
        $url = $this->url . ($name ? '?name=' . $name : '');
        $url = $url . ($status ? (str_contains($url, '?') ? '&' : '?') . 'status=' . $status : '');
        $url = $url . ($gender ? (str_contains($url, '?') ? '&' : '?') . 'gender=' . $gender : '');

        // Consumo del API y respuesta
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        return response()->json(json_decode($response->getBody()), 200);
    }

    public function zip () {
        // Consumo del API
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->url);
        $json_data = $response->getBody();

        // Creación del archivo
        $zip_file = 'trii.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('data.json', $json_data);
        $zip->close();

        // Respuesta y descarga
        return response()->download($zip_file);
    }
}
