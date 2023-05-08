<?php

namespace Sruuua\Application\Command;

use Sruuua\Application\Command;

class StartCommand extends Command
{
    protected string $call = 'start';

    protected string $description = 'Start the php server';

    public function __invoke()
    {
        echo 'Starting development server' . PHP_EOL;
        exec('open http://localhost:8000');
        if (PHP_OS === 'Linux')
            exec('make run');
        else
            exec('php -S 127.0.0.1:8000 -t public');
    }
}
