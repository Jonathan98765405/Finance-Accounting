<?php

namespace App\Mail;


use App\Models\AccountPayable\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RemittanceAdviceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment,
        public string $emailSubject,
        public string $emailMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.remittance-advice',
            with: [
                'payment' => $this->payment,
                'messageBody' => $this->emailMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(Storage::disk('local')->path($this->payment->remittance_pdf_path))
                ->as('Remittance-Advice-' . $this->payment->remittance_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}