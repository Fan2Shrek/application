<?php

namespace Sruuua\Application\Exception;

class NotFoundException extends \Exception
{
    public function __toString(): string
    {
        return $this->message;
    }
}
