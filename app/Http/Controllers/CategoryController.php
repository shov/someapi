<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
}
