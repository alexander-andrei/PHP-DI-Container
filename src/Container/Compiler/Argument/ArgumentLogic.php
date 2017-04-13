<?php

namespace Container\Compiler\Argument;

/**
 * Class ArgumentLogic
 * @package Container\Compiler\Argument
 *
 * @author Andrei.Caprar
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
                $returnType .= $this->_createReturnType($params, $a);

                if ($count != count($argument) - 1)
                {
                    $returnType .= ', ';
                }

                $count++;
            }
        }
        else
        {
            $returnType .= $this->_createReturnType($params, $argument);
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

    /**
     * @param array $params
     * @param string $a
     * @return string
     */
    private function _createReturnType(array $params, string $a)
    {
        if (is_numeric($a))
        {
            return sprintf("%s", $a);
        }
        elseif(class_exists($a))
        {
            return sprintf("new %s()", $a);
        }
        elseif($this->_checkIfArgumentIsService($a))
        {
            $a = ltrim($a, self::SERVICE_DELIMITER);

            return sprintf("\$this->get_%s()", $a);
        }
        elseif($this->_checkIfArgumentIsParam($a))
        {
            $a = ltrim($a, self::PARAMETER_DELIMITER);

            if (is_numeric($params[$a]))
            {
                return sprintf("%s", $params[$a]);
            }
            else
            {
                return sprintf("'%s'", $params[$a]);
            }
        }
        else
        {
            return sprintf("'%s'", $a);
        }
    }
}