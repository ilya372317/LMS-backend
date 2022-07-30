<?php

namespace App\CLI;

use phpDocumentor\Reflection\Types\Resource_;

class CommandLine implements CommandLineContract
{
    public function openReadStream()
    {
        $stream = fopen("php://stdin", 'r');
        stream_set_blocking($stream, false);
        return $stream;
    }

    public function closeStream($stream): bool
    {
        return fclose($stream);
    }

    public function getUserInput(): string
    {
        while (true) {
            $readStreams = [STDIN];
            $writeStreamsFake = [];
            $exceptStreamsFake = [];
            $timeout = 2;
            $countOfOpenedStreams = stream_select(
                $readStreams, $writeStreamsFake,
                $exceptStreamsFake, $timeout
            );

            if ($countOfOpenedStreams > 0) {
                return $this->readInput();
            }
        }
    }

    public function writeOutput(): bool
    {
        return false;
    }

    public function readInput(): string
    {
        return fgets(STDIN);
    }
}