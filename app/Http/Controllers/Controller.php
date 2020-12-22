<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function send_request($type = 'get', $url, $headers, $body){
        return $type == 'get' ?
                Http::withHeaders($headers)->get($url, $body) :
                Http::withHeaders($headers)->post($url, $body);
    }
}
