<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;
use App\Mail\EnquiryReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FormsController extends Controller
{
    public function show($hashedId)
    {
        $form = Form::whereHashedId($hashedId)->firstOrFail();

        $fields = $form->version->fields()
            ->with('type', 'options')
            ->get();

        return view('forms.show', compact('form', 'fields'));
    }

    public function submit(Request $request, $hashedId)
    {
        $form = Form::whereHashedId($hashedId)->firstOrFail();

        $version = $form->version;

        $data = $request->all();
        $rules = $version->rules();

        $this->validateEnquiry($data, $rules, $hashedId);

        $enquiry = $version->saveEnquiry($data);

        // Todo: Only if subscribed...
        if ($form->notification_email) {
            Mail::to($form->notification_email)->send(
                new EnquiryReceived($enquiry)
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true
            ]);
        }

        if ($form->redirect_url) {
            return redirect($form->redirect_url);
        }

        return redirect()
            ->route('forms.show', $hashedId)
            ->with('success', true);
    }

    protected function validateEnquiry(array $data, array $rules, $hashedId)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw (new ValidationException($validator))->redirectTo(
                route('forms.show', $hashedId)
            );
        }
    }
}
