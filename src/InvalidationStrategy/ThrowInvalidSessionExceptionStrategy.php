<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Loculus\SessionSecurityBundle\Exception\InvalidSessionException;

class ThrowInvalidSessionExceptionStrategy implements InvalidationStrategyInterface
{
    private const NAME = 'throw_invalid_session_exception_strategy';

    public function getName(): string
    {
        return self::NAME;
    }
    
    public function execute(): void
    {
        throw new InvalidSessionException('Session validation failed');
    }
}
