<?php

class DestinationLinkReplaceWorker implements ReplaceWorkerInterface
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
            $siteOfQuote = SiteRepository::getInstance()->getById($this->quote->getSiteId());
            $quote = QuoteRepository::getInstance()->getById($this->quote->getId());

            $destinationLink = ($siteOfQuote && $destinationOfQuote && $quote)
                ? sprintf('%s/%s/quote/%s', $siteOfQuote->getUrl(), $destinationOfQuote->getCountryName(), $quote->getId())
                : '';

            $message = str_replace($pattern, $destinationLink, $message);
        }
    }
}