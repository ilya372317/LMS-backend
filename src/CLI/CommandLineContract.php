<?php

namespace App\CLI;

use App\Exception\InputValidateException;
use phpDocumentor\Reflection\Types\Resource_;

interface CommandLineContract
{

    /**
     * Open CLI stream
     *
     * @throws InputValidateException
     * @return bool|resource
     */
    public function openReadStream();

    /**
     * close CLI stream
     *
     * @param resource $stream
     * @return bool
     */
    public function closeStream($stream): bool;

    /**
     * Wait while user type input and return it.
     *
     * @return string
     */
    public function getUserInput(): string;

    /**
     * Read input from CLI.
     *
     * @return string
     */
    public function readInput(): string;

    /**
     * write output to CLI.
     *
     * @return bool
     */
    public function writeOutput(): bool;

}