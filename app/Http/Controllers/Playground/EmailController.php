<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Helpers\MailHelper;

class EmailController extends Controller
{
    /**
     * Show the email test form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Return the view for the email sending form
        return view('playground.email');
    }

    /**
     * Send a test email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'to_name' => 'required|string|max:255',
            'to_email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // If validation fails, redirect back with errors and input
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Assuming MailHelper::sendEmail exists and handles the actual sending/logging and error handling internally
        // and returns true on success, false on failure.
        // You would need to ensure MailHelper is properly defined and imported if it's a custom class.
        // If MailHelper is not a custom class, you might intend to use the Mail facade directly here.
        // For demonstration, I'll assume MailHelper exists and works as intended based on your code structure.
        // If you don't have a MailHelper, you'd replace this call with the Mail::raw() or similar logic.
        $success = MailHelper::sendEmail(
            $request->to_name,
            $request->to_email,
            $request->subject,
            $request->message
        );

        if ($success) {
            return redirect()
                ->back()
                ->with('success', 'Test email processed successfully (check logs if using log driver)!');
        }

        // If MailHelper::sendEmail returned false, redirect back with an error
        return redirect()
            ->back()
            ->with('error', 'Failed to process test email')
            ->withInput();
    }
}
