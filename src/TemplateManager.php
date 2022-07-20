<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        $replaced = clone($tpl);

        $subject = $this->computeText($replaced->getSubject(), $data);
        $replaced->setSubject($subject);

        $content = $this->computeText($replaced->getContent(), $data);
        $replaced->setContent($content);

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
            $quoteFromRepository = QuoteRepository::getInstance()->getById($quote->getId());
            $siteOfQuote = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false) {

                $htmlView = (new RenderHtml($quoteFromRepository))
                    ->render();

                $text = str_replace(
                    '[quote:summary_html]',
                    $htmlView,
                    $text
                );
            }

            if ($containsSummary !== false) {
                $textView = (new RenderText($quote))
                    ->render();

                $text = str_replace(
                    '[quote:summary]',
                    $textView,
                    $text
                );
            }

            $containsDestinationName = strpos($text, '[quote:destination_name]');
            if ($containsDestinationName !== false) {
                $text = str_replace('[quote:destination_name]', $destinationOfQuote->getCountryName(), $text);
            }
        }

        $destinationLink = ($siteOfQuote && $destinationOfQuote && $quoteFromRepository)
            ? sprintf('%s/%s/quote/%s', $siteOfQuote->getUrl(), $destinationOfQuote->getCountryName(), $quoteFromRepository->getId())
            : '';

        $text = str_replace('[quote:destination_link]', $destinationLink, $text);

        /*
         * USER
         * [user:*]
         */
        $user = (isset($data['user']) and ($data['user'] instanceof User)) ? $data['user'] : $applicationContext->getCurrentUser();
        $containsFirstName = strpos($text, '[user:first_name]');
        if ($user && $containsFirstName !== false) {
            $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->getFirstname())), $text);
        }

        return $text;
    }
}
