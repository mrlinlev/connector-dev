<?php

namespace Leveon\Connector\Util;

use stdClass;

class CurlOutResponse
{
    private int $code;
    private array $headers;
    private string $content;
    private bool $successful;

    public function __construct($ch, $response){
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $this->parseHeaders($header);
        $this->content = substr($response, $header_size);
        $this->successful = intdiv($this->code, 100) === 2;
    }

    public static function Parse($ch, $response): CurlOutResponse
    {
        return new self($ch, $response);
    }

    private function parseHeaders(string $headers): void
    {
        foreach (explode("\n", $headers) as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1])) {
                $name = trim($t[0]);
                $value = trim($t[1]);
                if (!isset($this->headers[$name]))
                    $this->headers[$name] = $value;
                else
                    $this->headers[$name] .= "\n" . $value;
            } else {
                if (preg_match("#HTTP/[0-9.]+\s+([0-9]+)#", $v, $out)) {
                    $this->code = intval($out[1]);
                    $this->headers = [];
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param $key
     * @param null $default
     * @return string | null
     */
    public function getHeader($key, $default = null): string | null
    {
        return $this->headers[$key] ?? $default;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function isFailed(): bool
    {
        return $this->successful === false;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function json(): stdClass{
        return json_decode($this->content);
    }

}