<?php

namespace Container\Compiler;

/**
 * Interface PhpCompilerInterface
 * @package Container\Compiler
 *
 * @author Andrei.Caprar
 */
interface PhpCompilerInterface
{
    /**
     * Compile the services.
     *
     * @param string $serviceName
     * @return mixed
     */
    public function compile(string $serviceName);
}