<?php

namespace Sruuua\Application\Command;

use Sruuua\Application\Command;

class InitCommand extends Command
{
    protected string $call = 'init';

    protected string $description = 'Execute to be ready to work !';

    public function __invoke()
    {
        echo 'Init sruuua project ....' . PHP_EOL;
        echo 'Running composer install' . PHP_EOL;
        exec('composer install -q');
        echo "Should I launch the local server ? [\e[32my\e[0m/\e[91mn\e[0m]\n";
        $resp = strtolower(readline("  >"));
        if ($resp === 'yes' || $resp == 'y')
            exec('php sruuua start');
        echo "Now run \e[1mphp sruuua start\e[0m to start developing" . PHP_EOL . PHP_EOL;
    }
}
