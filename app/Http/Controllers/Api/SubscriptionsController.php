<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function subscribe(Request $request)
    {
        // Todo: Check if user is already subscribed...
        // If yes:
        //     * Make "token_id" non-required
        //     * "swap" subscription instead of "new"
        // If no:
        //     * "token_id" is required
        //     * newSubscription

        $user = $request->user();

        $tokenId = $request->input('token_id');
        $planId = $request->input('plan_id');

        if ($user->subscribed('primary')) {
            $user->subscription('primary')->change($planId);
        } else {
            $user->newSubscription('primary', $planId)->create($tokenId);
        }

        return response()->noContent();
    }

    public function unsubscribe(Request $request)
    {
        $user = $request->user();

        $user->subscription('primary')->cancel();

        return response()->noContent();
    }

    protected function validateSubscription(Request $request)
    {
        $this->validate($request, [
            'plan_id' => 'required|in:basic,premium,enterprise',
            'token_id' => 'required'
        ]);
    }
}
