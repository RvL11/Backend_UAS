<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationApiController extends Controller
{
    public function verify(Request $request) {
        $userId = $request['id'];
        $user = User::findOrFail($userId);
        $date = date("Y-m-d g:i:s");
        $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        $user->save();
        return redirect()->to('http://localhost:8081/login?verified=success%27');
    }

    /**
    * Resend the email verification notification.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
        return response()->json('User already have verified email!', 422);
        // return redirect($this->redirectPath());
        }
        
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
        // return back()->with(‘resent’, true);
    }
}
