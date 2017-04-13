<?php

namespace Container\Compiler\Validator;

/**
 * Interface ServiceValidatorInterface
 * @package Container\Compiler\Validator
 *
 * @author Andrei.Caprar
 */
interface ServiceValidatorInterface
{
    /**
     * Keys used for extracting parameters from the service yml file.
     */
    const SERVICES_KEY = 'services';
    const PARAMETERS_KEY = 'parameters';

    /**
     * Checks if services are registered in the service yml file.
     *
     * @param array $data
     * @return mixed
     */
    public function checkIfServicesExist(array $data);

    /**
     * Checks if parameters are registered in the service yml file.
     *
     * @param array $data
     * @return mixed
     */
    public function checkIfParametersExist(array $data);
}