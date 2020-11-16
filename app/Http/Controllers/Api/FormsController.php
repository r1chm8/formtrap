<?php

namespace App\Http\Controllers\Api;

use App\Form;
use Illuminate\Http\Request;
use App\Http\Resources\FormResource;

class FormsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $forms = $request->user()->forms()
            ->with('version')
            ->orderBy('name')
            ->get();

        return FormResource::collection($forms);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $form = new Form();

        $form->user_id = $request->user()->id;
        $form->name = $request->input('name');
        $form->redirect_url = $request->input('redirect_url');
        $form->notification_email = $request->input('notification_email');

        $form->save();

        return new FormResource($form);
    }

    public function show($id)
    {
        $form = Form::findOrFail($id);

        $this->authorize('manage', $form);

        return new FormResource($form);
    }

    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $this->authorize('manage', $form);

        $this->validateForm($request, $form);

        if ($request->has('name')) {
            $form->name = $request->input('name');
        }

        if ($request->has('redirect_url')) {
            $form->redirect_url = $request->input('redirect_url');
        }

        if ($request->has('notification_email')) {
            $form->notification_email = $request->input('notification_email');
        }

        $form->save();

        return new FormResource($form);
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        $this->authorize('manage', $form);

        $form->delete();

        return response()->noContent();
    }

    protected function validateForm(Request $request, Form $form = null)
    {
        $this->validate($request, [
            'name' => ($form ? 'filled' : 'required') . '|string|max:255',
            'notification_email' => 'nullable|email',
            'redirect_url' => 'nullable|url'
        ]);
    }
}
