<?php

namespace Container\Compiler\File;

/**
 * Interface FileHandlerInterface
 * @package Container\Compiler\File
 */
interface FileHandlerInterface
{
    /**
     * Compiled container extension.
     */
    const CONTAINER_FILE_EXTENSION = '.php';

    /**
     * Returns data from a service file.
     *
     * @param string $fileLocation
     * @return array
     */
    public function getFileData(string $fileLocation) : array;

    /**
     * Creates container file based on passed parameter.
     *
     * @param string $className
     * @return mixed
     */
    public function createContainerFile(string $className);

    /**
     * Retrieves the container file contents.
     *
     * @return string
     */
    public function getContainerFileContents() : string;

    /**
     * Adds services to the container file.
     *
     * @param string $data
     * @return mixed
     */
    public function appendServicesToContainerFile(string $data);
}