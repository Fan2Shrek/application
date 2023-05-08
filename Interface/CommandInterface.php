<?php

namespace Sruuua\Application\Interface;

interface CommandInterface
{
    public function getCall(): string;
    public function __invoke();
}
