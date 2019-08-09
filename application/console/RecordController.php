<?php

class RecordController {

    const API_KEY = 'FeqIlRFOGaRhFFNS5wPiSmN1lDu4l46X';

    public function loadAction()
    {
        $post = $this->getJsonParams(0);
        $result = shell_exec("curl -H -X POST -d ". escapeshellarg("data=$post")." http://3.121.243.25/fapi/apiStatistics.php");
        $data = json_decode($result);
        $total = $data->total;
        $offset = 10;
        $records = [];

        foreach ($data->data as $record) {
            $records[] = $record;
        }

        for ($i = $offset; $i < $total; $i+= $offset) {
            $post =  $this->getJsonParams($i);
            $result = shell_exec("curl -H -X POST -d ". escapeshellarg("data=$post")." http://3.121.243.25/fapi/apiStatistics.php");
            $data = json_decode($result);
            foreach ($data->data as $record) {

                $records[] = $record;
            }
        }
        foreach ($records as $record) {
            $connection = new PDO('mysql:host=localhost;dbname=asteriskcdrdb;charset=utf8', 'root', '02890ca2-1fe8-4d8b-8b12-6d8a304e0e87');
            $sql = "INSERT INTO cdr_tmp (calldate, cnum, dst, dstchannel, channel, duration, billsec, disposition, uniqueid, recordingfile, urlrecord)
                VALUES (:calldate, :cnum, :dst, :dstchannel, :channel, :duration, :billsec, :disposition, :uniqueid, :recordingfile, :urlrecord)";
            $connection->prepare($sql)->execute((array)$record);
        }

    }

    private function getJsonParams($offset)
    {
        return json_encode([
            "api_key" => self::API_KEY,
            "extension" => ["3000","3001","3002","3003","3004","3005","3006","3007","3008","3009","3010","3011","3012","3013","3014","3015","3016","3017","3018","3019","3020"],
            "date_start"=> date("Y-m-d H:i:s", strtotime("yesterday")),
            "date_end"=> date("Y-m-d H:i:s", strtotime("today 1 sec ago")),
            "offset"=> $offset
        ]);
    }

}


(new RecordController())->loadAction();