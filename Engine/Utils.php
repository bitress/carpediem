<?php

class Utils
{

    /**
     * Get user ip address
     * @return mixed
     */
    public static function getUserIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Generate string that will be used as fingerprint.
     * This is actually string created from user's browser name and user's IP address, so if someone steals users session, he won't be able to access.
     * @return string Generated string.
     */
    public static function generateLoginString(): string
    {
        $userIP = self::getUserIpAddress();
        $userBrowser = $_SERVER['HTTP_USER_AGENT'];
        return hash('sha512',$userIP . $userBrowser);
    }


    /**
     * Generate md5 key using time and login string
     * @return string Generated key.
     */
    public static function generateKey(): string
    {
        $uniquekey = self::generateLoginString();

        return md5(time() . $uniquekey . time());
    }


    /**
     * Generates random password
     * @param int $length Length of generated password
     * @return string Generated password
     */
    public static function randomPassword($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }



}