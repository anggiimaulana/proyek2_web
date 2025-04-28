<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AgamaControllerApi extends Controller
{
    public function index()
    {
        $agama = Agama::all();
        $response = [
            'error' => false,
            'message' => 'Data Agama',
            'data' => $agama,
        ];
        return response()->json($response, HttpFoundationResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
