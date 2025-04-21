<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(version: '1', title: 'Api Documentation'),
    OA\Server(url: 'http://localhost"8080/api', description: 'Local documentation')
]
abstract class Controller
{
    //
}
