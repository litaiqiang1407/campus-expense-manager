<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotFoundController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('NotFound/Index');
    }
}