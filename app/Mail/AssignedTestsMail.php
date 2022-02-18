<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignedTestsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * AssignedTestsMail constructor.
     *
     * @param User   $author
     * @param User   $user
     * @param string $url
     */
    public function __construct(private User $author, private User $user, private string $url)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.assigned_test')
            ->with([
                'user' => $this->user,
                'author' => $this->author,
                'url' => $this->url
            ]);
    }


}
