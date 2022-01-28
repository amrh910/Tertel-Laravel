<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;

class HelperController extends Controller
{   
    
    
    function getDetails($movies)
    {
        //self explanatory
        $main = array();
        
        foreach($movies as $movie)
        {
            $details = (new ApiController)->details($movie['type'], $movie['id']);
            $providers = (new ApiController)->providers($movie['type'], $movie['id']);
            $detail = array();
            $detail['name'] = $movie['type'] == "tv" ? $details['original_name'] : $details['original_title'];
            $detail['year'] = substr($movie['type'] == "tv" ? $details['first_air_date'] : $details['release_date'], 0, 4);
            $detail['rating'] = $details['vote_average'];
            $detail['popularity'] = $details['popularity'];
            $detail['poster'] = $details['poster_path'];
            $detail['stream'] = $providers;
            array_push($main, $detail);
            unset($details);
            unset($detail);
        }

        return $main;
    }

    function search($query)
    {
        $search = array();
        
        //prepare query for url
        $query = urlencode($query);
        
        //search title in movies then shows
        $movies = (new ApiController)->api("movie", $query);
        $shows = (new ApiController)->api("tv", $query);

        //insert results in final array and manage memory
        foreach($movies['results'] as $res)
        {
            $res['type'] = "movie";
            array_push($search, $res);
        }
        unset($movies);
        foreach($shows['results'] as $res)
        {
            $res['type'] = "tv";
            array_push($search, $res);
        }
        unset($shows);
        
        //sort DESC by popularity
        usort($search, function($a, $b) {
            return $b['popularity'] <=> $a['popularity'];
        });

        $main = $this->getDetails($search);
        unset($search);

        return $main;
    }
}
