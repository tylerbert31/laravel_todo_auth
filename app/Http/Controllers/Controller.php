<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    //
    public function log($message, $method = '', $line = ''){
        Log::info("$method ($line) ==> " . json_encode($message));
    }
}
