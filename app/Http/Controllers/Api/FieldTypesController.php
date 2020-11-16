<?php

namespace App\Http\Controllers\Api;

use App\FieldType;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldTypeResource;

class FieldTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $types = FieldType::all();

        return FieldTypeResource::collection($types);
    }

    public function show($id)
    {
        $type = FieldType::findOrFail($id);

        return new FieldTypeResource($type);
    }
}
