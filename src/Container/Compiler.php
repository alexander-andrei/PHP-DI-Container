<?php

namespace Container;


use Symfony\Component\Yaml\Yaml;

class Compiler
{
    private $_containerData;
    private $_services;

    public function __construct()
    {
        $this->_containerData = null;
    }

    public function compile($className)
    {
        $data = Yaml::parse(file_get_contents('services.yml'));

        $this->_services = $data['services'];

        $filename = $this->createContainerFile($className);

        $this->_containerData = file_get_contents($filename);
        $this->compileHead($className);
        $this->compileServices($data);
        $this->compileFooter($className);
    }

    private function compileServices($data)
    {
        if (isset($data['services']))
        {
            foreach ($data['services'] as $key =>$service)
            {
                $this->_containerData .= sprintf("public function get_%s(){\n", $key);

                if (isset($service['arguments']))
                {
                    $this->checkArgumentType($service['class'], $service['arguments']);
                }
                else
                {
                    $this->_containerData .= sprintf("return new %s();\n", $service['class']);
                }

                $this->_containerData .= "}\n";
            }
        }
    }

    private function checkArgumentType($service, $argument)
    {
        $isService = $this->checkIfArgumentIsService($argument);

        if (is_numeric($argument))
        {
            $this->_containerData .= sprintf("return new %s(%s);\n", $service, $argument);
        }
        elseif(class_exists($argument))
        {
            $this->_containerData .= sprintf("return new %s(new %s());\n", $service, $argument);
        }
        elseif($isService)
        {
            $argument = ltrim($argument, '@');

            $this->_containerData .= sprintf("return new %s(\$this->get_%s());\n", $service, $argument);
        }
        else
        {
            $this->_containerData .= sprintf("return new %s('%s');\n", $service, $argument);
        }
    }

    private function checkIfArgumentIsService($argument)
    {
        if ($argument[0] == '@')
        {
            return true;
        }

        return false;
    }

    private function createContainerFile($className)
    {
        $filename = $className . '.php';

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
        file_put_contents($className . '.php', $this->_containerData);
    }
}