<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Http\ControllerContract;
use App\Core\Http\Request;

class StaticPageController extends ControllerContract
{
    public function home($request)
    {
        return 1;
    }

    public function myroute(Request $request)
    {
        $data = $this->sanitize([
            'something' => 'email',
        ]);

        $myvar = $data['something'];

        return view('home', ['myvar' => $myvar]);
    }
}