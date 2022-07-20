<?php

class QuoteSummaryHtmlReplaceWorker implements ReplaceWorkerInterface
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

            $htmlView = (new RenderHtml($quote))
                ->render();
            $message = str_replace($pattern, $htmlView, $message);
        }
    }
}