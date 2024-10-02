<?php
// Module: dotenv
// Description: Provides utility functions for reading environment variables from a .env file.
// Version: 1.0.0
// Notes:
//  - Wenn `.env` nicht existiert oder nicht lesbar ist, returned `dotenv()` für alle keys null
//  - `.env` muss im `KEY=VALUE` format sein
//  - Hat gerade kein support für multi-line oder Comments

/**
 * @package dotenv
 * @since 1.0.0
 */
class DotEnv
{
   private static $env = [];

   /**
    * Parsed `.env` in ein Array
    *
    * @return array
    */
   private static function sync(): array
   {
      if (!empty(self::$env)) {
         return self::$env;
      }

      self::$env = array_reduce(
         explode("\n", file_get_contents(".env")),
         function ($carry, $line) {
            $parts = explode("=", trim($line));
            if (count($parts) == 2) {
               $carry[trim($parts[0])] = trim($parts[1]);
            }
            return $carry;
         },
         []
      );

      return self::$env;
   }

   /**
    * Gibt den Wert von `$key` as `string` wieder, falls vorhanden.
    * Ansonsten returned es `null`
    *
    * @param string $key
    * @param string|null $fallback
    * @return string|null
    */
   public static function search(string $key, string|null $fallback = null): string|null
   {
      $env = self::sync();
      return $env[$key] ?? $fallback;
   }
}

/**
 * Parsed die `.env` Datei, und gibt den Wert von `$key` as `string` wieder, falls vorhanden.
 * Ansonsten returned es `null`
 *
 * @package dotenv
 * @since 1.0.0
 *
 * @param string $key
 * @param string|null $fallback
 * @return string|null
 */
function dotenv(string $key, string|null $fallback = null): string|null
{
   return DotEnv::search($key, $fallback);
}
