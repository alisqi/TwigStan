<?php

use AlisQI\TwigStan\Tests\AbstractTestCase;

class VarTokenTypeValidationTest extends AbstractTestCase
{
    public static function getPrimitiveTypes(): array
    {
        return [
            ['array'],
            ['bool'],
            ['float'],
            ['int'],
            ['string'],
            ['null'],
        ];
    }

    /**
     * @dataProvider getPrimitiveTypes
     */
    public function test_itAcceptsPrimitiveType(string $type): void
    {
        $this->env->createTemplate("{% var $type foo %}")
            ->render();

        $this->assertTrue(true);
    }

    /**
     * @dataProvider getPrimitiveTypes
     */
    public function test_itAcceptsNullablePrimitiveType(string $type): void
    {
        $this->env->createTemplate("{% var ?$type foo %}")
            ->render();

        $this->assertTrue(true);
    }

    public function test_itThrowsOnInvalidPrimitiveType(): void
    {
        $this->expectExceptionMessage('Invalid type "bad" for variable "foo" on line 1');

        $this->env->createTemplate('{% var bad foo %}')->render();
    }

    public function test_itAcceptsClassType(): void
    {
        $this->env->createTemplate("{% var \Exception foo %}")
            ->render();

        $this->assertTrue(true);
    }
    
    public function test_itAcceptsNullableClassType(): void
    {
        $this->env->createTemplate("{% var ?\Exception foo %}")
            ->render();

        $this->assertTrue(true);
    }
    
    public function test_itThrowsIfTypeStartsWithInvalidPunctuation(): void
    {
        $this->expectExceptionMessage('Unexpected punctuation at start of type');
        
        $this->env->createTemplate("{% var .string foo %}")
            ->render();
    }
}
