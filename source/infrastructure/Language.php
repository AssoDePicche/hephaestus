<?php

declare(strict_types=1);

namespace Infrastructure;

final class Language
{
    private static ?self $instance = null;

    private array $translations = [];

    private function __construct(private string $language)
    {
        $filename = sprintf("%s/../../resources/languages.json", __DIR__);

        if (!file_exists($filename)) {
            throw new \Exception(sprintf("'%s' not found", $filename));
        }

        $translations = json_decode(file_get_contents($filename), true);

        if (!key_exists($language, $translations)) {
            throw new \RuntimeException(sprintf("'%s' language not supported", $language));
        }

        $this->translations = $translations[$language];
    }

    public function get(string $key, array $replace = []): string
    {
        $translation = $this->translations[$key] ?? $key;

        foreach ($replace as $key => $value) {
            $translation = str_replace(sprintf("{%s}", $key), $value, $translation);
        }

        return $translation;
    }

    public function getCurrentLanguage(): string
    {
        return $this->language;
    }

    public static function new(string $language = 'en-US'): self
    {
        if (null === self::$instance) {
            self::$instance = new self($language);
        }

        return self::$instance;
    }
}
