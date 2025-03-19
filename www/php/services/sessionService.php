<?php
class SessionService {
    public function stop(): bool {
        return session_destroy();
    }

    public function start(): bool {
        return session_start();
    }

    public function set(mixed $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public function get(mixed $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    public function setObject(mixed $key, mixed $value): void {
        $_SESSION[$key] = serialize($value);
    }

    public function getObject(mixed $key): mixed {
        return isset($_SESSION[$key]) ? unserialize($_SESSION[$key]) : null;
    }
    
}
?>