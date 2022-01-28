<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function main(Request $request)
    {
        $query = $request->input('query');
        $main = (new HelperController)->search($query);

        return $main;
    }
}
