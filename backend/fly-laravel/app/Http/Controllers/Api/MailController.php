<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Mail\TicketMailableClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\MailRequest;

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

    public function sendConfirmationEmail(MailRequest $request)
    {
        Log::info('sendConfirmationEmail called');
        Log::info('Request data:', $request->all());

        $details = [
            'title' => 'Confirmation de réservation',
            'body' => 'Votre réservation a été confirmée. Nombre de passagers: ' . $request->nbPassengers . '. Prix total: ' . $request->totalPrice . ' €.'
        ];

        $recipientEmail = Auth::user()->email;

        Log::info('Recipient email:', ['email' => $recipientEmail]);

        try {
            Mail::to($recipientEmail)->send(new TicketMailableClass($details));
            Log::info('Email sent successfully');
        } catch (\Exception $e) {
            Log::error('Error sending email:', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Confirmation email sent successfully to ' . $recipientEmail]);
    }
}
