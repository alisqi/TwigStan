<?php

namespace AlisQI\TwigStan\Tests;

use Twig\Loader\FilesystemLoader;

class BadArgumentCountInMacroCallTest extends AbstractTestCase
{
    public function test_itDoesNotWarnForMatchingArgumentNumber(): void
    {
        $this->env->createTemplate(<<<EOF
            {% macro marco() %}{% endmacro %}
            {% macro polo(arg, gra) %}{% endmacro %}
            {{ _self.marco() }}
            {{ _self.polo(13, 37) }}
        EOF)->render();
        
        self::assertEmpty($this->errors, implode(', ', $this->errors));
    }
    
    public function test_itWarnsForTooManyArguments(): void
    {
        $this->env->createTemplate(<<<EOF
            {% macro marco() %}{% endmacro %}
            {{ _self.marco(1337) }}
        EOF)->render();
        
        self::assertCount(1, $this->errors);
        
        self::assertStringContainsString('marco', $this->errors[0]);
        self::assertStringContainsStringIgnoringCase('too many', $this->errors[0]);
    }
    
    public function test_itSupportsVarArgs(): void
    {
        $this->env->createTemplate(<<<EOF
            {% macro marco() %}
                {{ varargs|length }}
            {% endmacro %}
            {{ _self.marco(1337) }}
        EOF)->render();
        
        self::assertEmpty($this->errors, implode(', ', $this->errors));
        
        $this->env->createTemplate(<<<EOF
            {% macro marco(polo) %}
                {% if polo %}
                    {{ varargs|length }}
                {% endif %}
            {% endmacro %}
            {{ _self.marco(13, 37) }}
        EOF)->render();
        
        self::assertEmpty($this->errors, implode(', ', $this->errors));
    }
    
    public function test_itSupportsArrayDefaults(): void
    {
        $this->env->createTemplate(<<<EOF
            {% macro marco(polo = []) %}
                {{ polo|length }}
            {% endmacro %}
            {{ _self.marco() }}
        EOF)->render();
        
        self::assertEmpty($this->errors, implode(', ', $this->errors));
    }
    
    public function test_itWarnsForTooFewArguments(): void
    {
        self::markTestIncomplete('Cannot implement this feature. See README');
        
        $this->env->createTemplate(<<<EOF
            {% macro marco(polo = null) %}{% endmacro %}
            {{ _self.marco() }}
        EOF)->render();
        
        self::assertEmpty($this->errors, implode(', ', $this->errors));
        
        $this->env->createTemplate(<<<EOF
            {% macro marco(polo) %}{% endmacro %}
            {{ _self.marco() }}
        EOF)->render();
        
        self::assertCount(1, $this->errors);
        
        self::assertStringContainsString('marco', $this->errors[0]);
        self::assertStringContainsStringIgnoringCase('too few', $this->errors[0]);
        
        $this->env->createTemplate(<<<EOF
            {% macro marco(po, lo = true) %}{% endmacro %}
            {{ _self.marco(1337) }}
        EOF)->render();
        
        self::assertEmpty($this->errors, implode(', ', $this->errors));
    }

    public function test_importedMacro(): void
    {
        $this->env->setLoader(
            new FilesystemLoader(__DIR__ . '/fixtures')
        );
        
        $this->env->render('importedMacro.twig');
        
        self::assertCount(2, $this->errors);
        
        self::assertStringContainsString('importedMacro', $this->errors[0]);
        self::assertStringContainsString('local', $this->errors[0]);
        self::assertStringContainsStringIgnoringCase('too many', $this->errors[0]);
        
        self::assertStringContainsString('importedMacro', $this->errors[1]);
        self::assertStringContainsString('marco', $this->errors[1]);
        self::assertStringContainsStringIgnoringCase('too many', $this->errors[1]);
    }
}
