<?php
/**
 * User: andrey
 * Date: 22.07.19
 */

namespace application\models;

use application\core\Model;

class Record extends Model
{

    public function deleteTodayRecords(array $phones)
    {
        $dateStart = Date('Y-m-d H:i:s', strtotime('today midnight'));
        $dateEnd = Date('Y-m-d H:i:s', time());
        $deletedRecords = [];
        $sql_condition = " calldate >= '".$dateStart."' AND calldate < '".$dateEnd."'";

        $sql = "SELECT * FROM cdr
                WHERE ".$sql_condition." 
                AND disposition LIKE 'ANSWERED' 
                AND channel NOT LIKE 'Local/%'";

        foreach ($this->db->row($sql) as $row) {
            if(!in_array($row['dst'], $phones)) {
                $this->db->delete('DELETE FROM cdr WHERE id = :id', ["id" => $row['id']]);
                $deletedRecords[] = $row['dst'];
            }
        }

        $return_arr = array(
            'status' 	=> 'success',
            'data' 	=> $deletedRecords
        );

        return $return_arr;
    }
}