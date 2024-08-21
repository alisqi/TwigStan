<?php

namespace AlisQI\TwigStan;

use AlisQI\TwigStan\Inspection\BadArgumentCountInMacroCall;
use AlisQI\TwigStan\Inspection\InvalidType;
use AlisQI\TwigStan\Inspection\UndeclaredVariableInMacro;
use AlisQI\TwigStan\TokenParser\VarTokenParser;
use Twig\Extension\AbstractExtension;

class Extension extends AbstractExtension
{
    public function getNodeVisitors(): array
    {
        return [
            new InvalidType(),
            new BadArgumentCountInMacroCall(),
            new UndeclaredVariableInMacro(),
        ];
    }

    public function getTokenParsers(): array
    {
        return [
            new VarTokenParser(),
        ];
    }
    
}
