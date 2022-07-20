<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $applicationContext = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        $quoteFromRepository = null;
        $siteOfQuote = null;
        $destinationOfQuote = null;

        if ($quote) {
            $quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $siteOfQuote = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false) {
                $text = str_replace(
                    '[quote:summary_html]',
                    Quote::renderHtml($quoteFromRepository),
                    $text
                );
            }

            if ($containsSummary !== false) {
                $text = str_replace(
                    '[quote:summary]',
                    Quote::renderText($quoteFromRepository),
                    $text
                );
            }

            $containsDestinationName = strpos($text, '[quote:destination_name]');
            if ($containsDestinationName !== false) {
                $text = str_replace('[quote:destination_name]', $destinationOfQuote->countryName, $text);
            }
        }

        $destinationLink = ($siteOfQuote && $destinationOfQuote && $quoteFromRepository)
            ? sprintf('%s/%s/quote/%s', $siteOfQuote->url, $destinationOfQuote->countryName, $quoteFromRepository->id)
            : '';

        $text = str_replace('[quote:destination_link]', $destinationLink, $text);

        /*
         * USER
         * [user:*]
         */
        $user = (isset($data['user']) and ($data['user'] instanceof User)) ? $data['user'] : $applicationContext->getCurrentUser();
        $containsFirstName = strpos($text, '[user:first_name]');
        if ($user && $containsFirstName !== false) {
            $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text);
        }

        return $text;
    }
}
