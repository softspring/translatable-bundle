<?php

namespace Softspring\TranslatableBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Softspring\TranslatableBundle\Model\Translation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');
        $translation->setTranslation('fr', 'Bonjour');
        $translation->setTranslation('it', 'Ciao');

        $encoded = json_encode($translation);

        $this->assertSame('{"en":"Hello","es":"Hola","fr":"Bonjour","it":"Ciao","_trans_id":null,"_default":"en"}', $encoded);
    }

    public function testTranslateUsesRequestedLocaleAndFallsBackToDefaultLocale(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');

        $this->assertSame('Hola', $translation->translate('es'));
        $this->assertSame('Hello', $translation->translate('fr'));
    }

    public function testTranslateUsesCurrentRequestLocaleWhenAvailable(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');

        $request = new Request();
        $request->setLocale('es');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $translation->__setRequestStack($requestStack);

        $this->assertSame('Hola', $translation->translate());
        $this->assertSame('Hola', (string) $translation);
    }

    public function testTranslateFallsBackToDefaultLocaleWhenNoCurrentRequestExists(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');
        $translation->__setRequestStack(new RequestStack());

        $this->assertSame('Hello', $translation->translate());
    }
}
