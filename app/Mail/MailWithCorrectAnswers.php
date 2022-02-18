<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailWithCorrectAnswers extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private User $user, private array $answers)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->markdown('emails.correct_answers')
            ->with([
                'answers' => $this->answers,
                'user' => $this->user
            ]);
    }
}
