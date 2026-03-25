<?php

namespace Softspring\TranslatableBundle\Tests\Unit\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Softspring\TranslatableBundle\Doctrine\Type\TranslationType;

class TranslationTypeTest extends TestCase
{
    public function testConvertToDatabaseValueFailsCleanlyForInvalidValues(): void
    {
        $type = new TranslationType();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Expected Softspring\TranslatableBundle\Model\Translation class, but array instance received');

        $type->convertToDatabaseValue(['en' => 'Hello'], $this->createStub(AbstractPlatform::class));
    }
}
