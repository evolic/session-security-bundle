<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

class RemoteAddress implements ValidatorInterface
{
    private const NAME = 'REMOTE_ADDRESS';

    private ?string $data;

    public function __construct(mixed $data = null)
    {
        if ($data === null) {
            $data = $this->getRemoteAddress();
        }

        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return $this->getData() === $this->getRemoteAddress();
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

    private function getRemoteAddress(): ?string
    {
        // @fixme Handle connections via proxy
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
}
