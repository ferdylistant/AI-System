<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TesWebsocketEvent;

class WebSocketController extends Controller
{
    public function index() {
        $data = 'Data coba-coba';
        broadcast(new TesWebsocketEvent($data));
        return;

    }
}
