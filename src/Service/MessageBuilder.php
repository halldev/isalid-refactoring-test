<?php

class MessageBuilder
{
    private string $messageContent;
    private array $params = [];

    public function __construct(string $content)
    {
        $this->messageContent = $content;
    }

    /**
     * @param string $pattern
     * @param ReplaceWorkerInterface $worker
     * @return $this
     */
    public function addParam(string $pattern, ReplaceWorkerInterface $worker): self
    {
        $this->params[$pattern] = $worker;
        return $this;
    }

    /**
     * @return $this
     */
    public function processBuild(): self
    {
        /**
         * @var string $pattern
         * @var ReplaceWorkerInterface $value
         */
        foreach ($this->params as $pattern => $replaceWorker) {
            if (method_exists($replaceWorker, 'replace')) {
                $replaceWorker->replace($this->messageContent, $pattern);
                continue;
            }

            throw new InvalidReplaceWorkerException();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageContent(): string
    {
        return $this->messageContent;
    }
}