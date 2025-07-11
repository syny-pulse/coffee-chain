<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkDistributionController extends Controller
{
    public function index(Request $request)
    {
        return view('processor.workDistribution');
    }
}
