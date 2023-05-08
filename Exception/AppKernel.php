<?php

namespace Sruuua\Application;

use Composer\Autoload\ClassLoader;
use Sruuua\Kernel\BaseKernel;

class AppKernel extends BaseKernel
{
    public function __construct(ClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
        $this->InitializeContainer();
    }
}