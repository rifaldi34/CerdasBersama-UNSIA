<?php
/**
 * CodeIgniter 4 Helper: Random Generator
 *
 * Provides functions for generating random strings and UUIDv7 identifiers.
 *
 * Usage:
 * 1. Place this file in app/Helpers/random_helper.php
 * 2. In app/Config/Autoload.php, add 'random' to $helpers array:
 *    public $helpers = ['random'];
 * 3. Call functions anywhere in your application:
 *    echo random_string(16);
 *    echo uuidv7();
 */

if (! function_exists('random_string'))
{
    /**
     * Generate a random string of given length using a-z0-9 (lowercase)
     *
     * @param int $length Number of characters
     * @return string
     * @throws \Exception
     */
    function random_string(int $length = 8): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $max        = strlen($characters) - 1;
        $result     = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, $max)];
        }

        return $result;
    }
}

if (! function_exists('uuidv7'))
{
    /**
     * Generate a UUID version 7 (timestamp-first) as a 32-character hex string
     *
     * @return string
     * @throws \Exception
     */
    function uuidv7(): string
    {
        // 16 random bytes
        $bytes = random_bytes(16);

        // Current timestamp in milliseconds since Unix epoch
        $timestamp = (int) (microtime(true) * 1000);

        // Insert 48-bit timestamp into first 6 bytes (big-endian)
        $bytes[0] = chr(($timestamp >> 40) & 0xFF);
        $bytes[1] = chr(($timestamp >> 32) & 0xFF);
        $bytes[2] = chr(($timestamp >> 24) & 0xFF);
        $bytes[3] = chr(($timestamp >> 16) & 0xFF);
        $bytes[4] = chr(($timestamp >> 8) & 0xFF);
        $bytes[5] = chr($timestamp & 0xFF);

        // Set version to 7 (0b0111xxxx)
        $bytes[6] = chr((ord($bytes[6]) & 0x0F) | 0x70);

        // Set variant to RFC 4122 (0b10xxxxxx)
        $bytes[8] = chr((ord($bytes[8]) & 0x3F) | 0x80);

        // Return as hex string without separators
        return bin2hex($bytes);
    }
}
