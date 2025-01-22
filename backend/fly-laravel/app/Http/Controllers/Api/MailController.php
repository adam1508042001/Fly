<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Mail\TicketMailableClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    public function sendMail()
    {
        $details = [
            'title' => 'Mail from Laravel',
            'body' => 'This is a test mail sent from Laravel.'
        ];

        $recipientEmail = Auth::user()->email;

        Mail::to($recipientEmail)->send(new TicketMailableClass($details));

        return response()->json(['message' => 'Email sent successfully to ' . $recipientEmail]);
    }
}
