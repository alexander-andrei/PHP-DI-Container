<?php

namespace Container;


use Symfony\Component\Yaml\Yaml;

class Compiler
{
    private $_containerData;
    private $_services;
    private $_params;

    const ARGUMENTS_KEY = 'arguments';

    const CLASS_KEY = 'class';

    const CONTAINER_FILE_EXTENSION = '.php';

    const SERVICE_DELIMITER = '@';

    const PARAMETER_DELIMITER = '%';

    const SERVICES_KEY = 'services';

    const PARAMETERS_KEY = 'parameters';

    public function __construct()
    {
        $this->_containerData = null;
    }

    public function compile($className)
    {
        $data = Yaml::parse(file_get_contents('services.yml'));

        $this->checkIfServicesExist($data);
        $this->checkIfParametersExist($data);

        $filename = $this->createContainerFile($className);

        $this->_containerData = file_get_contents($filename);
        $this->compileHead($className);
        $this->compileServices();
        $this->compileFooter($className);
    }

    private function checkIfServicesExist($data)
    {
        if (isset($data[self::SERVICES_KEY]))
        {
            $this->_services = $data[self::SERVICES_KEY];
        }
        else
        {
            throw new \Exception('Cannot compile Container without any services !');
        }
    }

    private function checkIfParametersExist($data)
    {
        if (isset($data[self::PARAMETERS_KEY]))
        {
            $this->_params = $data[self::PARAMETERS_KEY];
        }
        else
        {
            throw new \Exception('Cannot compile Container without any parameters !');
        }
    }

    private function compileServices()
    {
        foreach ($this->_services as $key =>$service)
        {
            $this->_containerData .= sprintf("public function get_%s(){\n", $key);

            if (isset($service[self::ARGUMENTS_KEY]))
            {
                $this->checkArgumentType($service[self::CLASS_KEY], $service[self::ARGUMENTS_KEY]);
            }
            else
            {
                $this->_containerData .= sprintf("return new %s();\n", $service[self::CLASS_KEY]);
            }

            $this->_containerData .= "}\n";
        }
    }

    private function checkArgumentType($service, $argument)
    {
        if (is_numeric($argument))
        {
            $this->_containerData .= sprintf("return new %s(%s);\n", $service, $argument);
        }
        elseif(class_exists($argument))
        {
            $this->_containerData .= sprintf("return new %s(new %s());\n", $service, $argument);
        }
        elseif($this->checkIfArgumentIsService($argument))
        {
            $argument = ltrim($argument, self::SERVICE_DELIMITER);

            $this->_containerData .= sprintf("return new %s(\$this->get_%s());\n", $service, $argument);
        }
        elseif($this->checkIfArgumentIsParam($argument))
        {
            $argument = ltrim($argument, self::PARAMETER_DELIMITER);

            if (is_numeric($this->_params[$argument]))
            {
                $this->_containerData .= sprintf("return new %s(%s);\n", $service, $this->_params[$argument]);
            }
            else
            {
                $this->_containerData .= sprintf("return new %s('%s');\n", $service, $this->_params[$argument]);
            }
        }
        else
        {
            $this->_containerData .= sprintf("return new %s('%s');\n", $service, $argument);
        }
    }

    private function checkIfArgumentIsService($argument)
    {
        if ($argument[0] == self::SERVICE_DELIMITER)
        {
            return true;
        }

        return false;
    }

    private function checkIfArgumentIsParam($argument)
    {
        if ($argument[0] == self::PARAMETER_DELIMITER)
        {
            return true;
        }

        return false;
    }

    private function createContainerFile($className)
    {
        $filename = $className .self::CONTAINER_FILE_EXTENSION;

        if (file_exists($filename))
        {
            unlink($filename);
        }

        $file = fopen($filename, 'w');
        fclose($file);

        return $filename;
    }

    private function compileHead($className)
    {
        $this->_containerData .= sprintf("<?php \n class %s {\n", $className);
        $this->_containerData .= "public function get(\$service){\n";
        $this->_containerData .= "\$method = sprintf('get_%s', \$service);\n";
        $this->_containerData .= "return \$this->\$method();\n";
        $this->_containerData .= "}\n";
    }

    private function compileFooter($className)
    {
        $this->_containerData .= "}";
        file_put_contents($className .self::CONTAINER_FILE_EXTENSION, $this->_containerData);
    }
}