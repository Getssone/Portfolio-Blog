<?php

namespace App\Model;

class SessionModel
{

    public function __construct()
    {
        $this->ensureStarted();
    }

    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE/*1*/) {
            session_start();
        }
    }

    public function getStatus()
    {
        return session_status();
    }


    public function reset()
    {
        return session_reset();
    }


    public function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE/*2*/) {
            session_destroy();
        }
    }

    /**
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->ensureStarted();
        if (isset($_SESSION)) {
            if (array_key_exists($key, $_SESSION)) {
                return $_SESSION[$key];
            }
            return $default;
        }
    }

    /**
     * @param  string $key
     * @param  $value
     * @return mixed
     */
    public function set(string $key, $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function deleteKey(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    /**
     * All session variables
     *
     * @return void
     */
    public function getAll()
    {
        $this->ensureStarted();
        return $_SESSION;
    }
}
