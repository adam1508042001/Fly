<?php
namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\PDF;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $pdf;

    public function __construct(Booking $booking, $pdf)
    {
        $this->booking = $booking;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Confirmation de réservation - ' . $this->booking->id)
                    ->view('emails.booking_email', ['booking' => $this->booking])
                    ->attachData($this->pdf->output(), 'booking_confirmation.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
