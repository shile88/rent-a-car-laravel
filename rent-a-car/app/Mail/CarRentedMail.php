<?php

namespace App\Mail;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarRentedMail extends Mailable
{
    use Queueable, SerializesModels;

    private $car;
    /**
     * Create a new message instance.
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function build()
    {
        $this->from(config('mail.from.address'), config('mail.from.name'))
        ->subject('Car rentals')
        ->view('mail.rentals', ['car' => $this->car]);
    }
}
