<?php

/**
 *
 */

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\KeyProtectedByPassword;

class Encryption
{
    /**
     * @var string|null
     */
    private string $user_key;

    public function __construct()
    {
        $this->user_key = Session::get('secret');
    }

    /**
     * Creates a new random password-protected key and returns it as an ASCII-safe string.
     *
     * @param string $password
     * @return string
     * @throws EnvironmentIsBrokenException
     */
    public static function createKey(string $password): string
    {
        $protect_key = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);
        return $protect_key->saveToAsciiSafeString();
    }

    /**
     * Authenticates a user by verifying their password and returns the user key as an ASCII-safe string.
     *
     * @param string $password
     * @param string $protected_key_encoded
     * @return string
     * @throws EnvironmentIsBrokenException
     * @throws BadFormatException
     * @throws WrongKeyOrModifiedCiphertextException
     */
    public static function authenticateUser(string $password, string $protected_key_encoded): string
    {
        $protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
        $user_key = $protected_key->unlockKey($password);
        return $user_key->saveToAsciiSafeString();
    }

    /**
     * Encrypts sensitive data using the user's key and returns the encrypted data.
     *
     * @param string $sensitive_data
     * @return string
     * @throws EnvironmentIsBrokenException
     */
    public function encrypt(string $sensitive_data): string
    {
        return Crypto::encrypt($sensitive_data, $this->user_key);
    }

    /**
     * Decrypts encrypted data using the user's key and returns the decrypted data.
     *
     * @param string $encrypted_data
     * @return string
     * @throws WrongKeyOrModifiedCiphertextException
     * @throws EnvironmentIsBrokenException
     */
    public function decrypt(string $encrypted_data): string
    {
        return Crypto::decrypt($encrypted_data, $this->user_key);
    }
}
