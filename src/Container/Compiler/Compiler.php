<?php

namespace Container\Compiler;


use Container\Compiler\Argument\ArgumentLogic;
use Container\Compiler\File\FileHandler;
use Container\Compiler\Validator\GeneralServiceValidator;

/**
 * Class Compiler
 * @package Container\Compiler
 *
 * @author Andrei.Caprar
 */
class Compiler implements PhpCompilerInterface
{
    /**
     * Keys used for extracting data from the service yml file.
     */
    const ARGUMENTS_KEY = 'arguments';
    const CLASS_KEY     = 'class';

    /**
     * @var null
     */
    private $_containerData;

    /**
     * @var array
     */
    private $_services;

    /**
     * @var array
     */
    private $_params;

    /**
     * @var ArgumentLogic
     */
    private $_argumentLogic;

    /**
     * @var FileHandler
     */
    private $_fileHandler;

    /**
     * @var GeneralServiceValidator
     */
    private $_serviceValidator;

    /**
     * Compiler constructor.
     */
    public function __construct()
    {
        $this->_containerData = null;

        $this->_argumentLogic       = new ArgumentLogic();
        $this->_fileHandler         = new FileHandler();
        $this->_serviceValidator    = new GeneralServiceValidator();
    }

    /**
     * @param string $className
     * @param string $serviceFileLocation
     *
     * @return null
     */
    public function compile(string $className, string $serviceFileLocation)
    {
        $data               = $this->_fileHandler->getFileData($serviceFileLocation);
        $this->_services    = $this->_serviceValidator->checkIfServicesExist($data);
        $this->_params      = $this->_serviceValidator->checkIfParametersExist($data);

        $this->_fileHandler->createContainerFile($className);

        $this->_containerData = $this->_fileHandler->getContainerFileContents();

        $this->_compileTop($className);
        $this->_compileServices();
        $this->_compileBottom();
    }

    /**
     * Compiles the services into the container file.
     */
    private function _compileServices()
    {
        foreach ($this->_services as $key =>$service)
        {
            $this->_containerData .= sprintf("public function get_%s(){\n", $key);
            $this->_containerData .= "static \$count = 0;\$count++;if (\$count > 3) {throw new Exception('You have a circular dependency in the service' . __METHOD__);}";
            if (isset($service[self::ARGUMENTS_KEY]))
            {
                $this->_containerData .= $this
                    ->_argumentLogic
                    ->checkArgumentType($service[self::CLASS_KEY], $service[self::ARGUMENTS_KEY], $this->_params);
            }
            else
            {
                $this->_containerData .= sprintf("return new %s();\n", $service[self::CLASS_KEY]);
            }

            $this->_containerData .= "}\n";
        }
    }

    /**
     * Compiles the top portion of the services file.
     *
     * @param string $className
     */
    private function _compileTop(string $className)
    {
        $this->_containerData .= sprintf("<?php \n class %s {\n", $className);
        $this->_containerData .= "public function get(\$service){\n";
        $this->_containerData .= "\$method = sprintf('get_%s', \$service);\n";
        $this->_containerData .= "return \$this->\$method();\n";
        $this->_containerData .= "}\n";
    }

    /**
     * Compiles the bottom portion of the services file.
     */
    private function _compileBottom()
    {
        $this->_containerData .= "}";
        $this->_fileHandler->appendServicesToContainerFile($this->_containerData);
    }
}