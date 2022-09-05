<?php
declare(strict_types=1);

namespace Svystunov\Projectorl3;

class Auth
{
    const FILENAME = 'auth.json';

    /**
     * @return false|array
     */
    public static function getAuthData()
    {
        if (!file_exists(self::FILENAME)) {
            return false;
        }

        return json_decode(file_get_contents(self::FILENAME), true);
    }
}
