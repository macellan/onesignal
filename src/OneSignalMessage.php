<?php

declare(strict_types=1);

namespace Macellan\OneSignal;

class OneSignalMessage
{
    private ?string $appId = null;

    private array $contents;

    private array $headings;

    private ?array $data = null;

    private ?string $icon = null;

    private ?string $webUrl = null;

    public static function create(string|array $body = ''): static
    {
        return new static($body);
    }

    public function __construct(string|array $body = '')
    {
        $this->setBody($body);
        $this->setSubject(config('app.name'));
    }

    protected function arrayValue(string|array $value): array
    {
        return (is_array($value)) ? $value : ['en' => $value];
    }

    /**
     * Set the message body.
     */
    public function setBody(string|array $value): static
    {
        $this->contents = $this->arrayValue($value);

        return $this;
    }

    /**
     * Set the message subject.
     */
    public function setSubject(string|array $value): static
    {
        $this->headings = $this->arrayValue($value);

        return $this;
    }

    /**
     * Set additional data.
     */
    public function setData(array $value): static
    {
        $this->data = $value;

        return $this;
    }

    public function setAppId(string $appId): static
    {
        $this->appId = $appId;

        return $this;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function setWebUrl(?string $webUrl = null): static
    {
        $this->webUrl = $webUrl;

        return $this;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getHeadings(): array
    {
        return $this->headings;
    }

    public function getBody(): array
    {
        return $this->contents;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getWebUrl(): ?string
    {
        return $this->webUrl;
    }
}
