<?php

namespace Container\Compiler\Argument;

/**
 * Interface ArgumentInterface
 * @package Container\Compiler\Argument
 *
 * Handles service argument logic.
 */
interface ArgumentInterface
{
    /**
     * Argument delimiters
     */
    const SERVICE_DELIMITER = '@';
    const PARAMETER_DELIMITER = '%';

    /**
     * Checks for argument type and creates string based on it's type.
     *
     * @param string $class
     * @param $argument
     * @param array $params
     * @return string
     */
    public function checkArgumentType(string $class, $argument, array $params) : string;
}