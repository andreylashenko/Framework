<?php

namespace application\common\controllers;

abstract class Controller
{
    abstract public function behaviors();

    abstract public function actions();
}
