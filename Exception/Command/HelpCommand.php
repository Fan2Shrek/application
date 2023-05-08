<?php

namespace Sruuua\Application\Command;

use Sruuua\Application\Command;

class HelpCommand extends Command
{
    protected string $call = 'help';

    protected string $description = 'If you need to know available commands';

    /**
     * @param Command[]
     */
    public function __invoke(array $commands = [])
    {
        $line = "+---------------------+--------------------------------------------------------------------+" . PHP_EOL;
        echo 'Need some help ?' . PHP_EOL;
        echo "List of available command :\n" . PHP_EOL;

        echo $line;
        echo ("|        Call         |                            Description                             |") . PHP_EOL;
        echo $line;

        foreach ($commands as $command) {
            $name = $command->getCall() . (str_repeat(' ', 20 - strlen($command->getCall())));
            $description = $command->getDescription() . (str_repeat(' ', 66 - strlen($command->getDescription())));

            echo sprintf("|%s | %s |", $name, $description) . PHP_EOL;
            echo $line;
        }
    }
}
