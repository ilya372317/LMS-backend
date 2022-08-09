<?php

namespace App\CLI;

abstract class CommandLine implements CommandLineContract
{
    public function unlockReadStream(): bool
    {
        return stream_set_blocking(STDIN, false);
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
                return $this->read();
            }
        }
    }

    abstract public function write(string $data): int|false;

    abstract public function read(): mixed;

    public function unlockWriteStream(): bool
    {
        return stream_set_blocking(STDOUT, false);
    }
}