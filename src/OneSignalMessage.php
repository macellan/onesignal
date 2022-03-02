<?php

namespace Macellan\OneSignal;

class OneSignalMessage
{
    private ?string $appId = null;

    private array $contents;

    private array $headings;

    private ?array $data = null;

    public static function create($body = ''): self
    {
        return new static($body);
    }

    /**
     * @param string|array $body
     */
    public function __construct($body = '')
    {
        $this->setBody($body);
        $this->setSubject(config('app.name'));
    }

    protected function arrayValue($value): array
    {
        return (is_array($value)) ? $value : ['en' => $value];
    }

    /**
     * Set the message body.
     *
     * @param string|array $value
     *
     * @return $this
     */
    public function setBody($value): self
    {
        $this->contents = $this->arrayValue($value);

        return $this;
    }

    /**
     * Set the message subject.
     *
     * @param string|array $value
     *
     * @return $this
     */
    public function setSubject($value): self
    {
        $this->headings = $this->arrayValue($value);

        return $this;
    }

    /**
     * Set additional data.
     *
     * @param array $value
     *
     * @return $this
     */
    public function setData(array $value): self
    {
        $this->data = $value;

        return $this;
    }

    /**
     * @param string $appId
     * @return $this
     */
    public function setAppId(string $appId): self
    {
        $this->appId = $appId;

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
}
