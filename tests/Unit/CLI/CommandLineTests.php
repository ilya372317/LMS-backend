<?php

namespace App\Tests\Unit\CLI;

use App\CLI\IntegerCommandLine;
use PHPUnit\Framework\TestCase;

class CommandLineTests extends TestCase
{
    public function testGetOneInputFromUser(): void
    {
        $commandLine = new IntegerCommandLine();
        $commandLine->write('write "123"');
        $userInput = $commandLine->getUserInput();

        $this->assertEquals("123\n", $userInput);
    }
}
