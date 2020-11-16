<?php

namespace App\Http\Controllers\Api;

use App\Form;
use App\Field;
use App\FieldType;
use Illuminate\Http\Request;
use App\Http\Resources\FieldResource;

class FieldsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index($formId, $versionId)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($versionId);

        $fields = $version->fields()->with('options')
            ->orderBy('order')
            ->get();

        return FieldResource::collection($fields);
    }

    public function store(Request $request, $formId, $versionId)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($versionId);

        $this->validateField($request);

        $field = new Field();

        $field->name = $request->input('name');
        $field->label = $request->input('label');
        $field->type_id = $request->input('type_id');
        $field->required = $request->input('required');

        // Meta
        $field->setMeta($request->only('multiple'));

        $version->fields()->save($field);

        // Options
        if ($field->typeRequiresOptions()) {
            $field->addOptions($request->input('options'));
        }

        return new FieldResource($field);
    }

    public function show($formId, $versionId, $id)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($versionId);

        $field = $version->fields()->findOrFail($id);

        return new FieldResource($field);
    }

    public function update(Request $request, $formId, $versionId, $id)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($versionId);

        $field = $version->fields()->findOrFail($id);

        $this->validateField($request);

        $hasOptions = $field->typeRequiresOptions();

        $field->name = $request->input('name');
        $field->label = $request->input('label');
        $field->type_id = $request->input('type_id');
        $field->required = $request->input('required');

        // Meta
        $field->setMeta($request->only('multiple'));

        $field->save();

        // Options
        if ($hasOptions) {
            $field->options()->delete();
        }

        if ($field->typeRequiresOptions()) {
            $field->addOptions($request->input('options'));
        }

        return new FieldResource($field);
    }

    public function move(Request $request, $formId, $versionId, $id)
    {
        $form = Form::findOrFail($formId);

        $version = $form->versions()->findOrFail($versionId);

        $field = $version->fields()->findOrFail($id);

        $this->validate($request, [
            'direction' => 'required|in:up,down'
        ]);

        $direction = $request->input('direction');

        if ($direction === 'up') {
            $field->moveOrderUp();
        } else {
            $field->moveOrderDown();
        }

        return response()->noContent();
    }

    public function destroy($formId, $versionId, $id)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($versionId);

        $field = $version->fields()->findOrFail($id);

        $field->delete();

        return response()->noContent();
    }

    protected function validateField(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255', // Todo: Unique
            'label' => 'required|string|max:255',
            'type_id' => 'required|exists:field_types,id'
        ];

        // Options
        if (FieldType::requiresOptions($request->input('type_id'))) {
            $rules = array_merge($rules, [
                'options' => 'required|array',
                'options.*' => 'array',
                'options.*.label' => 'required|string|max:255',
                'options.*.value' => 'nullable|string|max:255'
            ]);
        }

        // Multiple
        if ($request->input('type_id') == FieldType::SELECT) {
            $rules['multiple'] = 'boolean';
        }

        // Required
        $rules['required'] = 'boolean';

        // Todo: Error messages...

        $this->validate($request, $rules);
    }
}
