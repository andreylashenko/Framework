<?php
/**
 * User: andrey
 * Date: 12.07.19
 */

namespace application\dto;


class StatisticDto
{
    public $apiKey;
    public $dateStart;
    public $dateEnd;
    public $extension;
    public $leadPhone;
    public $offset;

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getLeadPhone()
    {
        return $this->leadPhone;
    }

    public function setLeadPhone($leadPhone)
    {
        $this->leadPhone = $leadPhone;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}
