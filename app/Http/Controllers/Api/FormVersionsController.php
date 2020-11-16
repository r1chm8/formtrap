<?php

namespace App\Http\Controllers\Api;

use App\Form;
use Carbon\Carbon;
use App\FieldOption;
use App\FormVersion;
use Illuminate\Http\Request;
use App\Http\Resources\FormVersionResource;

class FormVersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index($formId)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $versions = $form->versions()->latest()->get();

        return FormVersionResource::collection($versions);
    }

    public function store(Request $request, $formId)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $this->validateVersion($request);

        $version = new FormVersion();
        $version->form_id = $form->id;
        $version->name = $request->input('name', now()->toDateTimeString());
        $version->save();

        // Todo: Refactor...
        if ($request->filled('duplicate_of')) {
            $duplicate = FormVersion::find($request->input('duplicate_of'));

            $fields = $duplicate->fields()->with('options')->get();

            $options = [];

            foreach ($fields as $field) {
                $newField = $field->replicate();
                $newField->form_version_id = $version->id;
                $newField->save();

                foreach ($field->options as $option) {
                    $options[] = [
                        'field_id' => $newField->id,
                        'label' => $option->label,
                        'value' => $option->value
                    ];
                }
            }

            FieldOption::insert($options);
        }

        return new FormVersionResource($version);
    }

    public function show($formId, $id)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($id);

        return new FormVersionResource($version);
    }

    public function update(Request $request, $formId, $id)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $version = $form->versions()->findOrFail($id);

        $this->validateVersion($request);

        if ($request->filled('name')) {
            $version->name = $request->input('name');
        }

        if ($request->input('published')) {
            $version->published_at = Carbon::now();
        }

        $version->save();

        return new FormVersionResource($version);
    }

    public function destroy($formId, $id)
    {
        $form = Form::findOrFail($formId);

        $this->authorize('manage', $form);

        $form->versions()->findOrFail($id)->delete();

        return response()->noContent();
    }

    protected function validateVersion(Request $request)
    {
        $this->validate($request, [
            'name' => 'filled|string|max:255',
            // Todo: Only allow versions from current form...
            'duplicate_of' => 'exists:form_versions,id',
            'published' => 'bool'
        ]);
    }
}
