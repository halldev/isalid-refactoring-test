<?php

class RenderHtml implements RenderInterface
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function render(): string
    {
        return '<p>' . $this->quote->getId() . '</p>';
    }
}