<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

class HttpUserAgent implements ValidatorInterface
{
    private const NAME = 'user_agent';

    private ?string $data;

    public function __construct(mixed $data = null)
    {
        if ($data === null) {
            $data = $this->getHttpUserAgent();
        }

        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return $this->getData() === $this->getHttpUserAgent();
    }

    /**
     * @inheritDoc
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }

    private function getHttpUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
}
