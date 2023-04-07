<?php

namespace Sruuua\Application\Command;

use Sruuua\Application\Command;

class PingCommand extends Command
{
    protected string $call = 'ping';

    protected string $description = 'Very important to do before every git commit';

    public function __invoke()
    {
        die('pong' . PHP_EOL . PHP_EOL);
    }
}
