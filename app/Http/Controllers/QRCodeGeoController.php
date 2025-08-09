<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QRCodeGeoController extends Controller
{
    public function preview($code)
    {
        return view('qr.get-geo-data-script-page', [
            'code' => $code,
            'message' => 'Preparing your action…',
            'csrf' => csrf_token(),
        ]);
    }

    public function action(Request $request, string $code)
    {
        dd($request, $code);
    }
}
