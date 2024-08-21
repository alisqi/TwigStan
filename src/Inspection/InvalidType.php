<?php

namespace AlisQI\TwigStan\Inspection;

use AlisQI\TwigStan\Inspection\Error\TypeError;
use AlisQI\TwigStan\Node\VarNode;
use Twig\Environment;
use Twig\Node\Node;
use Twig\NodeVisitor\AbstractNodeVisitor;

class InvalidType extends AbstractNodeVisitor
{
    private const PRIMITIVE_TYPES = ['array', 'bool', 'float', 'int', 'string', 'null'];

    /**
     * @throws TypeError
     */
    protected function doEnterNode(Node $node, Environment $env): Node
    {
        if ($node instanceof VarNode) {
            $this->validateType($node->getType(), $node->getName(), $node->getTemplateLine());
        }

        return $node;
    }

    /**
     * @throws TypeError
     */
    private function validateType(string $type, string $name, int $lineNo): void
    {
        // remove optional ? prefix
        if (str_starts_with($type, '?')) {
            $type = substr($type, 1);
        }
        
        if (!in_array($type, self::PRIMITIVE_TYPES)) {
            throw new TypeError(sprintf(
                'Invalid type "%s" for variable "%s" on line %d',
                $type, $name, $lineNo
            ));
        }
    }

    protected function doLeaveNode(Node $node, Environment $env): ?Node
    {
        return $node;
    }

    public function getPriority(): int
    {
        return 0;
    }
}
