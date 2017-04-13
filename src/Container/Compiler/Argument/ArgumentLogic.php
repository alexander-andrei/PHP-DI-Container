<?php

namespace Container\Compiler\Argument;

/**
 * Class ArgumentLogic
 * @package Container\Compiler\Argument
 */
class ArgumentLogic implements ArgumentInterface
{
    /**
     * @param string $service
     * @param $argument
     * @param array $params
     * @return string
     */
    public function checkArgumentType(string $service, $argument, array $params) : string
    {
        $returnType = sprintf("return new %s(", $service);

        if (is_array($argument))
        {
            $count = 0;
            foreach ($argument as $a)
            {
                if (is_numeric($a))
                {
                    $returnType .= sprintf("%s", $a);
                }
                elseif(class_exists($a))
                {
                    $returnType .= sprintf("new %s()", $a);
                }
                elseif($this->_checkIfArgumentIsService($a))
                {
                    $a = ltrim($a, self::SERVICE_DELIMITER);

                    $returnType .= sprintf("\$this->get_%s()", $a);
                }
                elseif($this->_checkIfArgumentIsParam($a))
                {
                    $a = ltrim($a, self::PARAMETER_DELIMITER);

                    if (is_numeric($params[$a]))
                    {
                        $returnType .= sprintf("%s", $params[$a]);
                    }
                    else
                    {
                        $returnType .= sprintf("'%s'", $params[$a]);
                    }
                }
                else
                {
                    $returnType .= sprintf("'%s'", $a);
                }

                if ($count != count($argument) - 1)
                {
                    $returnType .= ', ';
                }

                $count++;
            }
        }

        $returnType .= ");";

        return $returnType;
    }

    /**
     * @param string $argument
     * @return bool
     */
    private function _checkIfArgumentIsService(string $argument) : bool
    {
        if ($argument[0] == self::SERVICE_DELIMITER)
        {
            return true;
        }

        return false;
    }

    /**
     * @param string $argument
     * @return bool
     */
    private function _checkIfArgumentIsParam(string $argument) : bool
    {
        if ($argument[0] == self::PARAMETER_DELIMITER)
        {
            return true;
        }

        return false;
    }
}