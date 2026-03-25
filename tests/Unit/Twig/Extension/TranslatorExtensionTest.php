<?php

namespace Softspring\TranslatableBundle\Tests\Unit\Twig\Extension;

use PHPUnit\Framework\TestCase;
use Softspring\TranslatableBundle\Model\Translation;
use Softspring\TranslatableBundle\Twig\Extension\TranslatorExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslatorExtensionTest extends TestCase
{
    public function testGetGlobalsExposeConfiguredApiDriver(): void
    {
        $extension = new TranslatorExtension(new RequestStack(), 'google');

        $this->assertSame([
            'sfs_translatable' => [
                'api' => [
                    'driver' => 'google',
                ],
            ],
        ], $extension->getGlobals());
    }

    public function testTranslateUsesCurrentRequestLocale(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');

        $request = new Request();
        $request->setLocale('es');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $extension = new TranslatorExtension($requestStack);

        $this->assertSame('Hola', $extension->translate($translation));
    }

    public function testTranslateFallsBackToDefaultLocaleWithoutCurrentRequest(): void
    {
        $translation = new Translation();
        $translation->setDefaultLocale('en');
        $translation->setTranslation('en', 'Hello');
        $translation->setTranslation('es', 'Hola');

        $extension = new TranslatorExtension(new RequestStack());

        $this->assertSame('Hello', $extension->translate($translation));
    }
}
