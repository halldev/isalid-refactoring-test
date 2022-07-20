<?php

class QuoteSummaryReplaceWorker implements ReplaceWorkerInterface
{
    private Quote $quote;

    public function __construct(Quote $quote = null)
    {
        $this->quote = $quote;
    }

    public function replace(&$message, string $pattern)
    {
        if ($this->quote && strpos($message, $pattern) !== false) {
            $quote = QuoteRepository::getInstance()->getById($this->quote->getId());

            $textView = (new RenderText($quote))
                ->render();
            $message = str_replace($pattern, $textView, $message);
        }
    }
}