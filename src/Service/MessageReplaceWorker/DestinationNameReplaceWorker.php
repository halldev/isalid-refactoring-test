<?php

class DestinationNameReplaceWorker implements ReplaceWorkerInterface
{
    private Quote $quote;

    public function __construct(Quote $quote = null)
    {
        $this->quote = $quote;
    }

    public function replace(&$message, string $pattern)
    {
        if ($this->quote && strpos($message, $pattern) !== false) {
            $destinationOfQuote = DestinationRepository::getInstance()->getById($this->quote->getDestinationId());

            $destinationName = $destinationOfQuote ? $destinationOfQuote->getCountryName() : null;
            $message = str_replace($pattern, $destinationName, $message);
        }
    }
}