<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    function api($type, $query)
    {
        $apiKey = env('TMDB_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.themoviedb.org/3/search/$type?api_key=$apiKey&language=en-US&query=$query&page=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        
        return $response;
    }

    function details($type, $id)
    {
        $apiKey = env('TMDB_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.themoviedb.org/3/$type/$id?api_key=$apiKey&language=en-US",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        return $response;
    }

    function providers($type, $id)
    {
        $apiKey = env('TMDB_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.themoviedb.org/3/$type/$id/watch/providers?api_key=$apiKey&language=en-US",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        //for now this is just for the US. Will expand later.
        //current support: NETFLIX, HULU, DISNEYPLUS, HBOMAX, PEACOCKTV, PRIMEVIDEO, FUBOTV
        $support = ['NETFLIX', 'HULU', 'DISNEYPLUS', 'HBOMAX', 'PEACOCK', 'PEACOCKPREMIUM', 'AMAZONPRIMEVIDEO', 'FUBOTV'];
        $results = array();
        
        if(isset($response['results']['US']['flatrate']))
        {
            foreach($response['results']['US']['flatrate'] as $res)
            {
                $provider = strtoupper(trim($res['provider_name']));
                if(in_array($provider, $support))
                {
                    array_push($results, $provider);
                }
            }
            unset($support);
            unset($response);
        }
        
        return $results;
    }
}
