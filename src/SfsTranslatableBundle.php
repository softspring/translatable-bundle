<?php

declare(strict_types=1);

namespace Softspring\TranslatableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsTranslatableBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
