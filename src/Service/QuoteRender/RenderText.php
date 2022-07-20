<?php

class RenderText implements RenderInterface
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function render(): string
    {
        return (string) $this->quote->id;
    }
}