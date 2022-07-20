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
        $user = (isset($data['user']) && ($data['user'] instanceof User)) ? $data['user'] : $applicationContext->getCurrentUser();

        $messageBuilder = (new MessageBuilder($text))
            ->addParam('[quote:destination_link]', new DestinationLinkReplaceWorker($quote))
            ->addParam('[quote:destination_name]', new DestinationNameReplaceWorker($quote))
            ->addParam('[quote:summary_html]', new QuoteSummaryReplaceWorker($quote))
            ->addParam('[quote:summary]', new QuoteSummaryReplaceWorker($quote))
            ->addParam('[user:first_name]', new UserNameReplaceWorker($user));

        return $messageBuilder
            ->processBuild()
            ->getMessageContent();
    }
}
