<?php

namespace Softspring\TranslatableBundle\Form\Type;

use Softspring\Component\DynamicFormType\Form\Resolver\TypeResolverInterface;
use Softspring\TranslatableBundle\Form\Transformer\TranslatableTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatableType extends AbstractType
{
    public function __construct(protected ?string $defaultLanguage, protected ?array $languages, protected ?TypeResolverInterface $typeResolver = null)
    {
    }

    public function getBlockPrefix(): string
    {
        return 'translatable';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'default_language' => $this->defaultLanguage,
            'languages' => $this->languages,
            'children_attr' => [],
            'type' => null,
            'type_options' => [],
            'default_locale_type_options' => null,
        ]);

        $resolver->setRequired('languages');
        $resolver->setAllowedTypes('languages', 'array');
        $resolver->setRequired('default_language');
        $resolver->setAllowedTypes('default_language', 'string');
        $resolver->setRequired('type');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('type_options', 'array');
        $resolver->setAllowedTypes('default_locale_type_options', ['array', 'null']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('_default', HiddenType::class);

        foreach ($options['languages'] as $lang) {
            $childrenOptions = [
                'required' => $lang == $options['default_language'],
                'label' => $lang,
                'translation_domain' => false,
                'block_prefix' => 'translatable_element',
                'attr' => [],
            ];

            if ($lang == $options['default_language']) {
                $typeOptions = null !== $options['default_locale_type_options'] ? $options['default_locale_type_options'] : $options['type_options'];
            } else {
                $typeOptions = $options['type_options'];
            }

            $childrenOptions = array_merge($childrenOptions, $typeOptions);

            $childrenOptions['attr'] = array_merge($childrenOptions['attr'] ?? [], $typeOptions['attr'] ?? [], [
                'data-input-lang' => $lang,
            ]);

            foreach ($options['children_attr'] ?? [] as $attr => $value) {
                $childrenOptions['attr'][$attr] = $value;
            }

            if ($lang !== $options['default_language']) {
                $childrenOptions['attr']['data-fallback-lang'] = $options['default_language'];
            }

            if ($this->typeResolver) {
                $options['type'] = $this->typeResolver->resolveTypeClass($options['type']);
            }

            $builder->add($lang, $options['type'], $childrenOptions);
        }

        $builder->addModelTransformer(new TranslatableTransformer($options));
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['localeFields'] = array_filter($view->children, function (FormView $child, string $locale) {
            return false === str_starts_with($locale, '_');
        }, ARRAY_FILTER_USE_BOTH);
    }
}
