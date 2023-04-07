<?php

namespace Sruuua\Application\Command;

use Sruuua\Application\Command;

class PresentationCommand extends Command
{
    protected string $call = 'presentation';

    protected string $description = 'Have some informations';

    public function __invoke()
    {
        echo "Welcome to \e[1msruuua\e(B\e[m framework" . PHP_EOL;
        echo "Made by \033[32mFan2Shrek\033[39m : \e[4mhttps://github.com/Fan2Shrek\e[24m" . PHP_EOL;
    }
}
