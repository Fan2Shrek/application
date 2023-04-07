<?php

namespace Sruuua\Application;

use Sruuua\Application\Interface\CommandInterface;
use Sruuua\Application\Exception\NotFoundException;

class Application
{
    /**
     * @var CommandInterface[]
     */
    private array $commandPool;

    private array $args;

    public function __construct(array $args)
    {
        $this->commandPool = array();
        $this->args = array_slice($args, 1, 2);
    }

    public function get(string $command)
    {
        if (empty($this->commandPool)) $this->registerCommand();
        return $this->commandPool[$command] ?? null;
    }

    public function addCommand(CommandInterface $command)
    {
        $this->commandPool[$command->getCall()] = $command;
    }

    public function execute(?string $commandName = null)
    {
        if (null === $commandName) $commandName = $this->args[0] ?? 'presentation';
        if (null === $command = $this->get($commandName)) throw new NotFoundException(sprintf("Command '%s' does not exist", $commandName));
        if ($commandName === 'help')
            return $command($this->commandPool);
        return $command();
    }

    public function registerCommand()
    {
        foreach ($this->getClassNames() as $commandClass) {
            $this->addCommand(new $commandClass());
        }
    }

    private function getClassNames()
    {
        $classNames = array();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('./src/Sruuua\Applicationlication/Command'));
        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $className = str_replace('.php', '', $file->getFilename());
            $fullClassName = str_replace('/', '\\', $file->getPath()) . '\\' . $className;
            $fullClassName = str_replace('.\src', 'Sruuua\Application', $fullClassName);
            $classNames[] = $fullClassName;
        }

        return $classNames;
    }
}
