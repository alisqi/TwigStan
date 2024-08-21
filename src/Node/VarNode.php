<?php

namespace AlisQI\TwigStan\Node;

use Twig\Node\Node;

/**
 * Declare a variable's type and ensure type matches if `strict_variables` is enabled.
 *
 *  {% var string foo %}
 *  {% var ?int bar %}
 *  {% var \Foo\Bar\Baz baz %}
 */
class VarNode extends Node
{
    public function __construct(string $type, string $name, int $lineno = 0, string $tag = null)
    {
        parent::__construct([], ['type' => $type, 'name' => $name], $lineno, $tag);
    }

    public function getType(): string
    {
        return $this->getAttribute('type');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}
