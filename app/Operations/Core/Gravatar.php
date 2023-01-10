<?php


namespace App\Operations\Core;

class Gravatar
{
    /**
     * Get a Gravatar Image and return the HTML image.
     * @param string|null $email
     * @return String
     */
    public static function get(?string $email) : string
    {
        $img = (new self)->getGravatar($email);
        return "$img";
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string|null $email The email address
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @link https://gravatar.com/site/implement/images/php/
     */
    private function getGravatar(?string $email, int $s = 50, string $d = 'mm', string $r = 'g', ?bool $img = false, array $atts = []):string
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
