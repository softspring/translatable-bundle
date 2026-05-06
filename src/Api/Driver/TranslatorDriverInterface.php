<?php

declare(strict_types=1);

namespace Softspring\TranslatableBundle\Api\Driver;

use Softspring\TranslatableBundle\Api\Model\ApiTranslation;

interface TranslatorDriverInterface
{
    /**
     * @param  array<string, mixed> $options
     * @throws TranslationException
     */
    public function translate(string $originText, string $targetLanguage, ?string $sourceLanguage = null, array $options = []): ApiTranslation;
}
