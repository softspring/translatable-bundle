<?php

namespace Softspring\TranslatableBundle\Tests\Unit\Form\Transformer;

use PHPUnit\Framework\TestCase;
use Softspring\TranslatableBundle\Form\Transformer\TranslatableTransformer;
use Softspring\TranslatableBundle\Model\Translation;

class TranslatableTransformerTest extends TestCase
{
    public function testTransformAddsDefaultLocaleToEmptyData(): void
    {
        $transformer = new TranslatableTransformer([
            'default_language' => 'en',
        ]);

        $this->assertSame([
            '_default' => 'en',
        ], $transformer->transform([]));
    }

    public function testTransformConvertsTranslationObjectToArray(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');

        $transformer = new TranslatableTransformer([
            'default_language' => 'en',
        ]);

        $data = $transformer->transform($translation);

        $this->assertSame('en', $data['_default']);
        $this->assertSame('Hello', $data['en']);
        $this->assertSame('Hola', $data['es']);
    }

    public function testReverseTransformAddsDefaultLocale(): void
    {
        $transformer = new TranslatableTransformer([
            'default_language' => 'en',
        ]);

        $data = $transformer->reverseTransform([
            'es' => 'Hola',
        ]);

        $this->assertSame([
            'es' => 'Hola',
            '_default' => 'en',
        ], $data);
    }
}
