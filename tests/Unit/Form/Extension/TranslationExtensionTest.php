<?php

namespace Softspring\TranslatableBundle\Tests\Unit\Form\Extension;

use PHPUnit\Framework\TestCase;
use Softspring\TranslatableBundle\Form\Extension\TranslationExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\RouterInterface;

class TranslationExtensionTest extends TestCase
{
    public function testFinishViewDoesNothingWhenApiDriverIsDisabled(): void
    {
        $router = $this->createStub(RouterInterface::class);
        $extension = new TranslationExtension($router, false);
        $view = $this->createTranslationView();

        $extension->finishView($view, $this->createStub(FormInterface::class), []);

        $this->assertArrayNotHasKey('translate_prepend_button', $view->children['es']->vars);
        $this->assertSame(['form', 'translation_element_widget'], $view->children['es']->vars['block_prefixes']);
    }

    public function testFinishViewAddsApiMetadataToNonDefaultLocaleFields(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())
            ->method('generate')
            ->with('sfs_translatable_api_translate')
            ->willReturn('/api/translate');

        $extension = new TranslationExtension($router, true, 'google');
        $view = $this->createTranslationView();

        $extension->finishView($view, $this->createStub(FormInterface::class), []);

        $this->assertArrayNotHasKey('translate_prepend_button', $view->children['en']->vars);
        $this->assertSame([
            'attr' => [
                'data-translate' => '',
                'data-translate-source-field' => 'form[en]',
                'data-translate-target-field' => 'form[es]',
                'data-translate-source-locale' => 'en',
                'data-translate-target-locale' => 'es',
                'data-translate-url' => '/api/translate',
            ],
            'api_driver' => 'google',
            'enabled' => true,
        ], $view->children['es']->vars['translate_prepend_button']);
        $this->assertContains('translation_element_widget_with_api', $view->children['es']->vars['block_prefixes']);
    }

    private function createTranslationView(): FormView
    {
        $view = new FormView();

        $view->children['_default'] = new FormView($view);
        $view->children['_default']->vars['value'] = 'en';

        $view->children['en'] = new FormView($view);
        $view->children['en']->vars['full_name'] = 'form[en]';
        $view->children['en']->vars['block_prefixes'] = ['form', 'translation_element_widget'];

        $view->children['es'] = new FormView($view);
        $view->children['es']->vars['full_name'] = 'form[es]';
        $view->children['es']->vars['block_prefixes'] = ['form', 'translation_element_widget'];

        return $view;
    }
}
