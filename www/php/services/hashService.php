<?php
class HashService
{
    /**
     * Erstellt einen Hash eines übergebenen Wertes mit Argon2.
     * 
     * @param string $value Der zu hashende Wert.
     * @return string Der erzeugte Hash.
    */
    public static function getHash(string $value): string
    {
        return password_hash($value, PASSWORD_ARGON2ID);
    }

    /**
     * Vergleicht einen ungehashen Wert mit einem vorhandenen Hash.
     * 
     * @param string $value Der ungehashte Wert.
     * @param string $hash Der gespeicherte Hash.
     * @return bool True, wenn der Wert dem Hash entspricht, sonst false.
     */
    public static function compareHash(string $value, string $hash): bool
    {
        return password_verify($value, $hash);
    }
}
/*
                   ,:',:`,:'
                __||_||_||_||___
           ____[""""""""""""""""]___
           \ " '''''''''''''''''''' \
    ~~jgs~^~^~^^~^~^~^~^~^~^~^~~^~^~^~^~~^~^

*/
?>
