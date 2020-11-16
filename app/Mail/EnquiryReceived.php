<?php

namespace App\Mail;

use App\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnquiryReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $enquiry;

    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function build()
    {
        return $this->markdown('emails.enquiry-received', [
            'enquiry' => $this->enquiry
        ]);
    }
}
