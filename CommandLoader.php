<?php

namespace Sruuua\Application;

use Composer\Autoload\ClassLoader;
use Sruuua\Application\Interface\CommandInterface;

class CommandLoader
{
    private array $commandList;

    public function __construct()
    {
        $this->commandList = array();
    }

    /**
     * @return CommandInterface[]
     */
    public function load(ClassLoader $classLoader): array
    {
        foreach ($classLoader->getPrefixesPsr4() as $vendorName => $value) {
            if (str_starts_with($vendorName, 'Sruuua\\')) {
                $this->findAllCommand($value[0], $vendorName);
            }
        }

        return $this->commandList;
    }

    public function findAllCommand(string $path, string $baseNamespace)
    {
        $commandPath = $path . '/Command';
        if (!\is_dir($commandPath)) return;

        $files = array_diff(scandir($commandPath), array('.', '..'));

        foreach ($files as $fileName) {
            $file = $commandPath . '/' . $fileName;
            $namespace = $baseNamespace . 'Command\\' . pathinfo($file, PATHINFO_FILENAME);
            if (!is_dir($file)) {

                if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
                $r = new \ReflectionClass($namespace);

                if ($r->isSubclassOf(Command::class)) {
                    $this->commandList[] = $namespace;
                }
            } else {
                $namespace .= '\\';
                $this->findAllCommand($file, $namespace);
            }
        }
    }
}
