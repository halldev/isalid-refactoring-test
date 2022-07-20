<?php

class UserNameReplaceWorker implements ReplaceWorkerInterface
{
    private User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function replace(&$message, string $pattern)
    {
        if ($this->user && strpos($message, $pattern) !== false) {
            $firstname = $this->user ? ucfirst(mb_strtolower($this->user->getFirstname())) : null;
            $message = str_replace($pattern, $firstname, $message);
        }
    }
}