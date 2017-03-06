<?php
class Utills
{
    static function generateSessionHash($dev_id)
    {
        $solt = '23s23$3#'.time();
        return md5($dev_id.$solt);
    }
}

?>