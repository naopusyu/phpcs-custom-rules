<?php

declare(strict_types=1);

namespace Naopusyu\PHPCSCustomRules\Tests\Sniffs\NamingConventions;

use PHPUnit\Framework\TestCase;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

class SnakeCaseVariableNameSniffTest extends TestCase
{
    public function test(): void
    {
        $fixtureFile = __DIR__ . '/fixture.php';
        $sniffFiles = [__DIR__ . '/../../../../src/PHPCSCustomRules/Sniffs/NamingConventions/SnakeCaseVariableNameSniff.php'];
        $config = new Config();
        $ruleset = new Ruleset($config);
        $ruleset->registerSniffs($sniffFiles, [], []);
        $ruleset->populateTokenListeners();
        $phpcsFile = new LocalFile($fixtureFile, $ruleset, $config);
        $phpcsFile->process();
        $foundErrors = $phpcsFile->getErrors();
        $lines = array_keys($foundErrors);
        $this->assertSame([4, 9, 14, 17, 20], $lines);
    }
}
