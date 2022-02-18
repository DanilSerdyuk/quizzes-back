<?php

namespace App\Listeners;

use App\Events\QuizWasCreated;
use App\Mail\MailWithCorrectAnswers;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class PrepareForSendMailWithCorrectAnswers implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  QuizWasCreated  $event
     * @return void
     */
    public function handle(QuizWasCreated $event): void
    {
        /** @var User $user */
        if (!$user = User::query()->whereKey($event->getUserId())->first()) {
            return;
        }

        $answers = $this->makeAnswerList($event->getQuestions());

        Mail::to($user->email)->send(new MailWithCorrectAnswers($user, $answers));
    }

    /**
     * @param array $questions
     *
     * @return array
     */
    private function makeAnswerList(array $questions): array
    {
        $result = [];

        foreach ($questions as $question) {
            $result[$question->title] = $question->correct_answer;
        }

        return $result;
    }
}
