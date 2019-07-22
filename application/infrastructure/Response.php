<?php

namespace application\infrastructure;

class Response
{

    const STATUS_OK = 200;

    public static function json($data, $error = null) {

        if ($data) {
            echo json_encode(["status" => "success", $data]);
        } else {
            echo json_encode(["status" => false, "errors" => $error]);
        }
        die;
    }
}
