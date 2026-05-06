<?php

declare(strict_types=1);

namespace Softspring\TranslatableBundle\Tests\Unit\Form\Transformer;

use PHPUnit\Framework\TestCase;
use Softspring\TranslatableBundle\Form\Transformer\TranslationTransformer;
use Softspring\TranslatableBundle\Model\Translation;

class TranslationTransformerTest extends TestCase
{
    public function testTransformCreatesTranslationDataForEmptyValues(): void
    {
        $transformer = new TranslationTransformer([
            'default_language' => 'en',
        ]);

        $data = $transformer->transform(null);

        $this->assertSame('en', $data['_default']);
        $this->assertNotEmpty($data['_trans_id']);
    }

    public function testTransformConvertsRawArrayToNormalizedTranslationArray(): void
    {
        $transformer = new TranslationTransformer([
            'default_language' => 'en',
        ]);

        $data = $transformer->transform([
            'es' => 'Hola',
            '_default' => 'es',
        ]);

        $this->assertSame('en', $data['_default']);
        $this->assertSame('Hola', $data['es']);
        $this->assertNotEmpty($data['_trans_id']);
    }

    public function testReverseTransformReturnsTranslationObject(): void
    {
        $transformer = new TranslationTransformer([
            'default_language' => 'en',
        ]);

        $translation = $transformer->reverseTransform([
            'en' => 'Hello',
            '_default' => 'en',
        ]);

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertSame('Hello', $translation->translate('en'));
    }

    public function testReverseTransformReturnsNullForEmptyValue(): void
    {
        $transformer = new TranslationTransformer([
            'default_language' => 'en',
        ]);

        $this->assertNull($transformer->reverseTransform([]));
    }
}
