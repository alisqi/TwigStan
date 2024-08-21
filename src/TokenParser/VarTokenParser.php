<?php

namespace AlisQI\TwigStan\TokenParser;

use AlisQI\TwigStan\Node\VarNode;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class VarTokenParser extends AbstractTokenParser
{
    /**
     * @inheritDoc
     */
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $nullableOperator = $stream->nextIf(Token::PUNCTUATION_TYPE)?->getValue() ?? '';
        $type = $stream->expect(Token::NAME_TYPE)->getValue();
        $name = $stream->expect(Token::NAME_TYPE)->getValue();

        $stream->expect(Token::BLOCK_END_TYPE);

        return new VarNode($nullableOperator . $type, $name, $lineno, $this->getTag());
    }

    /**
     * @inheritDoc
     */
    public function getTag(): string
    {
        return 'var';
    }
}
