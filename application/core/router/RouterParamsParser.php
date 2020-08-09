<?php

namespace application\core\router;

class RouterParamsParser
{
    public static function parse(&$params) {
        if ($params) {
            foreach ($params as $cnt => $param) {
                list($key, $value) = explode('=', $param);
                unset($params[$cnt]);
                $params[$key] = $value;
            }
        }

        $body_params = file_get_contents('php://input');
        if ($body_params) {
            $params['body'] = json_decode($body_params);
        }
    }
}