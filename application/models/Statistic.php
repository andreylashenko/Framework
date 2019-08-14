<?php
/**
 * User: andrey
 * Date: 12.07.19
 */

namespace application\models;


use application\core\Model;
use application\dto\StatisticDto;

class Statistic extends Model
{

    private $wav_url = 'http://3.121.243.25/rec_wav_url';
    private $mp3_url = 'http://3.121.243.25/rec_mp3_url';

    /**
     * @param StatisticDto $statisticDto
     * @return array
     */
    public function getRecords(StatisticDto $statisticDto)
    {

        if($statisticDto->dateStart) {
            $dateStart = trim($statisticDto->dateStart);
        } else {
            $dateStart = '2018-01-01 00:00:00';
        }

        if($statisticDto->dateEnd) {
            $dateEnd = trim($statisticDto->dateEnd);
        } else {
            $dateEnd = date("Y-m-d H:i:s", time());
        }

        $sql_condition = " calldate >= '".$dateStart."' AND calldate < '".$dateEnd."' ";

        if($statisticDto->extension && is_array($statisticDto->extension)) {
            $ext_search = implode(',',$statisticDto->extension);
            $sql_condition .= " AND cnum IN (".$ext_search.") ";
        }

        if($statisticDto->leadPhone) {
            $lead_phone = trim($statisticDto->leadPhone);
            $dst = substr($lead_phone,-10);
            $sql_condition .= " AND dst = '".$lead_phone."'";
        }

        if(isset($statisticDto->offset)) {
            $offset = trim($statisticDto->offset);
        } else {
            $offset = '0';
        }
        $sql = "SELECT * FROM cdr
                WHERE ".$sql_condition." 
                AND disposition LIKE 'ANSWERED' 
                AND channel NOT LIKE 'Local/%' 
                AND recordingfile NOT LIKE ''  
                ORDER BY calldate ASC LIMIT 10 OFFSET ".$offset;


        $callArr = array();
        foreach ($this->db->row($sql) as $row) {

            $cd = explode(' ',$row['calldate']);
            $ymd = explode('-',$cd[0]);

            $urlRecord = '';
            if(!empty($row['recordingfile'])){
                $rec_name = str_replace('.wav','',$row['recordingfile']);
                if($cd[0] == date("Y-m-d")){
                    $rec_f = $rec_name.'.wav';
                    $urlRecord = $this->wav_url.'/'.$ymd[0].'/'.$ymd[1].'/'.$ymd[2].'/'.$rec_f;
                }else{
                    $rec_f = $rec_name.'.mp3';
                    $urlRecord = $this->mp3_url.'/'.$ymd[0].'/'.$ymd[1].'/'.$ymd[2].'/'.$rec_f;
                }
            }

            $callArr[] = array(
                'calldate' => $row['calldate'],
                'cnum' => $row['cnum'],
                'dst' => $row['dst'],
                'dstchannel' => $row['dstchannel'],
                'channel' => $row['channel'],
                'duration' => $row['duration'],
                'billsec' => $row['billsec'],
                'disposition' => $row['disposition'],
                'uniqueid' => $row['uniqueid'],
                'recordingfile' => $row['recordingfile'],
                'urlrecord' => $urlRecord,

            );
        }
        //получаем кол-во записей(FIXME total is not working)
        $sql = "SELECT count(*) as total FROM asteriskcdrdb.cdr WHERE ".$sql_condition." AND disposition LIKE 'ANSWERED' AND channel NOT LIKE 'Local/%' AND recordingfile NOT LIKE '' ";


        $row = $this->db->row($sql)[0];
        if(isset($row) && isset($row['total'])) 	$total = $row['total'];
        else					$total = 0;


        $return_arr = array(
            'status' 	=> 'success',
            'data' 	=> $callArr,
            'total'	=> $total,
        );

        return  $return_arr;
    }

    /**
     * @param StatisticDto $statisticDto
     * @return array
     */
    public function getTodayRecords(StatisticDto $statisticDto)
    {
        if($statisticDto->dateStart) {
            $dateStart = trim($statisticDto->dateStart);
        } else {
            $dateStart = '2018-01-01 00:00:00';
        }

        if($statisticDto->dateEnd) {
            $dateEnd = trim($statisticDto->dateEnd);
        } else {
            $dateEnd = date("Y-m-d H:i:s", time());
        }

        $sql_condition = " calldate >= '".$dateStart."' AND calldate < '".$dateEnd."' ";

        if($statisticDto->extension && is_array($statisticDto->extension)) {
            $ext_search = implode(',',$statisticDto->extension);
            $sql_condition .= " AND cnum IN (".$ext_search.") ";
        }

        $sql = "SELECT * FROM cdr
                WHERE ".$sql_condition." 
                AND disposition LIKE 'ANSWERED' 
                AND channel NOT LIKE 'Local/%' 
                AND recordingfile NOT LIKE ''  
                ORDER BY calldate ASC";

        $callArr = array();
        foreach ($this->db->row($sql) as $row) {

            $cd = explode(' ',$row['calldate']);
            $ymd = explode('-',$cd[0]);

            $urlRecord = '';
            if(!empty($row['recordingfile'])){
                $rec_name = str_replace('.wav','',$row['recordingfile']);
                if($cd[0] == date("Y-m-d")){
                    $rec_f = $rec_name.'.wav';
                    $urlRecord = $this->wav_url.'/'.$ymd[0].'/'.$ymd[1].'/'.$ymd[2].'/'.$rec_f;
                }
            }

            $callArr[] = array(
                'calldate' => $row['calldate'],
                'cnum' => $row['cnum'],
                'dst' => $row['dst'],
                'dstchannel' => $row['dstchannel'],
                'channel' => $row['channel'],
                'duration' => $row['duration'],
                'billsec' => $row['billsec'],
                'disposition' => $row['disposition'],
                'uniqueid' => $row['uniqueid'],
                'recordingfile' => $row['recordingfile'],
                'urlrecord' => $urlRecord,

            );
        }

        $return_arr = array(
            'status' 	=> 'success',
            'data' 	=> $callArr
        );

        return  $return_arr;
    }
}
