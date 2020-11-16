<?php

namespace App\Http\Controllers\Api;

use App\Enquiry;
use Illuminate\Http\Request;
use App\Http\Resources\EnquiryResource;

class EnquiriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('store');
    }

    public function index(Request $request)
    {
        $enquiries = Enquiry::forUser($request->user())
            ->with([
                'form.version',
                'contents.field' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->whereHas('form')
            ->filter($request)
            ->latest()
            ->paginate(10);

        return EnquiryResource::collection($enquiries);
    }

    public function show($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        $this->authorize('manage', $enquiry);

        return new EnquiryResource($enquiry);
    }

    public function update(Request $request, $id)
    {
        $enquiry = Enquiry::findOrFail($id);

        $this->authorize('manage', $enquiry);

        $this->validate($request, [
            'read' => 'bool'
        ]);

        if ($request->filled('read')) {
            $enquiry->read = (bool) $request->input('read');
        }

        $enquiry->save();

        return new EnquiryResource($enquiry);
    }

    public function destroy($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        $this->authorize('manage', $enquiry);

        $enquiry->delete();

        return response()->noContent();
    }
}
