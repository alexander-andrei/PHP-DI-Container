<?php

namespace Container\Compiler\File;


use Symfony\Component\Yaml\Yaml;

/**
 * Class FileHandler
 * @package Container\Compiler\File
 *
 * @author Andrei.Caprar
 */
class FileHandler implements FileHandlerInterface
{
    /**
     * @var string
     */
    private $_serviceFileName;

    /**
     * @param string $fileLocation
     * @return array
     */
    public function getFileData(string $fileLocation)
    {
        $fileTimeName = 'changetime';

        $pastFileTime = null;
        $currentFileTime = filemtime($fileLocation);
        if (file_exists($fileTimeName))
        {
            $pastFileTime = (int) file_get_contents($fileTimeName);
        }
        else
        {
            file_put_contents($fileTimeName, filemtime($fileLocation));
        }

        if ($pastFileTime != $currentFileTime && $pastFileTime)
        {
            file_put_contents($fileTimeName, filemtime($fileLocation));
            return Yaml::parse(file_get_contents($fileLocation));
        }
        else
        {
            exit;
        }
    }

    /**
     * @param string $className
     * @return null
     */
    public function createContainerFile(string $className)
    {
        $this->_serviceFileName = $className .self::CONTAINER_FILE_EXTENSION;

        if (file_exists($this->_serviceFileName))
        {
            unlink($this->_serviceFileName);
        }

        $file = fopen($this->_serviceFileName, 'w');
        fclose($file);
    }

    /**
     * @return string
     */
    public function getContainerFileContents() : string
    {
        return file_get_contents($this->_serviceFileName);
    }

    /**
     * @param string $data
     * @return null
     */
    public function appendServicesToContainerFile(string $data)
    {
        file_put_contents($this->_serviceFileName, $data);
    }
}