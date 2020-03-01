<?php

namespace application\core;


class ExceptionHandler extends \Exception
{
    public function __construct($code, $message) {
        parent::__construct($message, $code, null);
        $this->ExceptionResponse($code, $message);
        die;
    }

    private function ExceptionResponse(int $code, string $message) {
        echo json_encode(['code' => $code, 'message' => $message]);
    }
}
