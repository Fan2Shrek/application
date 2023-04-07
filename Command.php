<?php

namespace Sruuua\Application;

use Sruuua\Application\Interface\CommandInterface;

abstract class Command implements CommandInterface
{
    protected string $call;

    protected string $description;

    /**
     * Get the value of call
     */
    public function getCall(): string
    {
        return $this->call;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }
}
