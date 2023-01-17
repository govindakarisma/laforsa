<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserResource;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return new AuthUserResource($request->user());
    }
}
