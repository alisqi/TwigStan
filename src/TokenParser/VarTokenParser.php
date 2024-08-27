<?php

namespace AlisQI\TwigStan\TokenParser;

use AlisQI\TwigStan\Node\VarNode;
use Twig\Error\SyntaxError;
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
        
        $nullable = false;
        
        $prefix = $stream->nextIf(Token::PUNCTUATION_TYPE)?->getValue();
        if ($prefix === '?') {
            $nullable = true;
            
            $prefix = $stream->nextIf(Token::PUNCTUATION_TYPE)?->getValue();
        }
        
        if ($prefix === '\\') { // (likely) start of FQN
            $fqn = '\\';
            
            $fqn .= $stream->expect(Token::NAME_TYPE)->getValue();
            // TODO: loop
            
            $type = $fqn;
        } else if ($prefix === null) {
            $type = $stream->expect(Token::NAME_TYPE)->getValue();
        } else {
            throw new SyntaxError(
                'Unexpected punctuation at start of type',
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()
            );
        }
        
        if ($nullable) {
            $type = "?$type";
        }
        
        $name = $stream->expect(Token::NAME_TYPE)->getValue();

        $stream->expect(Token::BLOCK_END_TYPE);

        return new VarNode($type, $name, $lineno, $this->getTag());
    }

    /**
     * @inheritDoc
     */
    public function getTag(): string
    {
        return 'var';
    }
}
