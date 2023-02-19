<?php

declare(strict_types=1);

namespace TBuilder\Helpers;

use Dotenv\Dotenv;

/**
 * Env load helper
 *
 * @package Src\Helpers
 * @author  Thiago Silva <thiagom.devsec@gmail.com>
 * @version 1.0
 */
final class Env
{

    /**
     * Default env path
     *
     * @var string
     */
    private const ENVPATH = __DIR__ . '/../../';

    /**
     * Load env values
     *
     * @param string|null $path
     * @return array
     */
    final public static function loadEnv(string|null $path = null): array
    {
        try {
            $path = !is_null($path) ? $path : self::ENVPATH;
            return (Dotenv::createImmutable($path))->load();
        } catch (\DomainException $e) {
           die("Message: {$e->getMessage()}");
        }
    }

}
