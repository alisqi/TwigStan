<?php

use AlisQI\TwigStan\Tests\AbstractTestCase;

class VarTokenTypeValidationTest extends AbstractTestCase
{
    public function test_itThrowsOnInvalidPrimitiveType(): void
    {
        $this->expectExceptionMessage('Invalid type "bad" for variable "foo" on line 1');
        
        $this->env->createTemplate(<<<EOF
            {% var bad foo %}
        EOF)->render();
    }
}
