<?php

namespace Container\Compiler\Validator;

/**
 * Class GeneralServiceValidator
 * @package Container\Compiler\Validator
 *
 * @author Andrei.Caprar
 */
class GeneralServiceValidator implements ServiceValidatorInterface
{
    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function checkIfServicesExist(array $data)
    {
        if (isset($data[self::SERVICES_KEY]))
        {
            return $data[self::SERVICES_KEY];
        }
        else
        {
            throw new \Exception('Cannot compile Container without any services !');
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function checkIfParametersExist(array $data)
    {
        if (isset($data[self::PARAMETERS_KEY]))
        {
            return $data[self::PARAMETERS_KEY];
        }
        else
        {
            throw new \Exception('Cannot compile Container without any parameters !');
        }
    }
}