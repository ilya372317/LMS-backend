<?php

namespace App\CLI;

interface CommandLineContract
{

    /**
     * Unlock input CLI stream.
     *
     * @return bool|
     */
    public function unlockReadStream(): bool;

    /**
     * Ublock output CLI stream.
     *
     * @return bool
     */
    public function unlockWriteStream(): bool;

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
    public function read(): mixed;

    /**
     * Send data to CLI.
     *
     * @param string $data
     * @return int|false
     */
    public function write(string $data): int|false;

}