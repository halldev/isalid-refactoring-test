<?php

interface ReplaceWorkerInterface
{
    public function replace(&$message, string $pattern);
}