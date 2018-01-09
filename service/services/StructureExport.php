<?php
namespace service\services;
use think\Db;
/**
 * Description of StructureExport
 *
 * @author 张启全
 */
class StructureExport {
    static public function getStructure($table_name,$exclude){
        $data=[];
        $desc=Db::query("SHOW FULL FIELDS FROM   $table_name");
        foreach ($desc as $key => $value) {
            $comment=$value['Field'];
            if(isset($value['Comment'])&&!empty($value['Comment']))$comment=$value['Comment'];
            if(!in_array($value['Field'], $exclude)){
                $data[$value['Field']]=$comment;
            }
        }
        return $data;
    }
}
