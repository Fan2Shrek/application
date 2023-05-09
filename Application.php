<?php

namespace Sruuua\Application;

use Composer\Autoload\ClassLoader;
use Sruuua\Application\Interface\CommandInterface;
use Sruuua\Application\Exception\NotFoundException;
use Sruuua\Cache\Cache;
use Sruuua\DependencyInjection\Container;
use Sruuua\Kernel\BaseKernel;
use Symfony\Component\Dotenv\Dotenv;

class Application
{
    /**
     * @var CommandInterface[]
     */
    private array $commandPool;

    private Container $container;

    private ClassLoader $classLoader;

    private BaseKernel $kernel;

    public function __construct(ClassLoader $classLoader)
    {
        $this->commandPool = array();
        $this->classLoader = $classLoader;
        $this->initializeKernel($this->classLoader);
        $this->loadEnv();
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

    public function execute(array $args)
    {
        if (count($args) == 1) $args[1] = 'presentation';
        if (null === $command = $this->get($args[1])) throw new NotFoundException(sprintf("Command '%s' does not exist", $command));

        if ($args[1] === 'help') {
            return $command($this->commandPool);
        }

        return $command();
    }

    public function getCommandDependencies(string $command)
    {
        $dependency = [];

        $refClass = new \ReflectionClass($command);

        if ($refClass->hasMethod('__construct')) {
            $construct = $refClass->getMethod('__construct');

            foreach ($construct->getParameters() as $param) {
                $dependency[] = $this->kernel->getContainer()->get($param->getType());
            }
        }

        return $dependency;
    }

    public function registerCommand()
    {
        $cachePool = $this->kernel->getContainer()->get('Sruuua\Cache\CachePool');

        if ($cachePool->hasItem('commandPool')) {
            $this->commandPool = $cachePool->getItem('commandPool')->get();
            return;
        }

        foreach ($this->loadCommands() as $commandClass) {
            $this->addCommand(new $commandClass(...$this->getCommandDependencies($commandClass)));
        }

        $cachePool->save(new Cache('commandPool', $this->commandPool));
    }

    private function loadCommands(): array
    {
        $container = $this->kernel->getContainer();
        $commandLoader = new CommandLoader();

        $container->set('commandLoader', $commandLoader);

        return $commandLoader->load($this->classLoader);
    }

    public function initializeKernel(ClassLoader $classLoader): BaseKernel
    {
        $this->kernel = new AppKernel($classLoader);

        return $this->kernel;
    }

    public function loadEnv()
    {
        $dotenv = new Dotenv();
        $dotenv->load('../.env');
    }
}
