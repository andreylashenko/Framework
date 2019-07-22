<?php
/**
 * User: andrey
 * Date: 22.07.19
 */

namespace application\controllers;


use application\core\Controller;
use application\dto\ClearRecordsDto;
use application\infrastructure\Response;

class RecordController extends Controller
{

    public function clearAction(ClearRecordsDto $clearRecordsDto)
    {
        if(!$clearRecordsDto->apiKey || $clearRecordsDto->apiKey !== self::API_KEY) {
            Response::json(null, 'api_key not found');
        }
        Response::json($this->model->deleteTodayRecords($clearRecordsDto->phones));
    }
}