<?php
/**
 * User: andrey
 * Date: 12.07.19
 */

namespace application\controllers;

use application\core\Controller;
use application\dto\StatisticDto;
use application\infrastructure\Response;

class StatisticController extends Controller
{

    public function getStatAction(string $api_key, StatisticDto $statisticDto)
    {

        if(!$api_key || $api_key !== self::API_KEY) {
            Response::json(null, 'api_key not found');
        }

        Response::json($this->model->getRecords($statisticDto));
    }
}
