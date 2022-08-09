<?php

namespace App\CLI;

class IntegerCommandLine extends CommandLine
{

    public function read(): int
    {
        $inputData = str_replace(["\n", ' '], '', fgets(STDIN));
        return (int) $inputData;
    }

    public function write(string $data): int|false
    {
        return fwrite(STDOUT, $data);
    }
}