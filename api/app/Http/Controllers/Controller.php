<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(version: '1', title: 'Api Documentation')
]
abstract class Controller
{
}
