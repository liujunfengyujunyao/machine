<?php
namespace Admin\Model;
use Think\Model;
class EquipmentModel extends Model{
	public function seven($startime,$endtime){
		$id = session('manager_info.id');
		$return = M('tbl_game_log')->alias('t1')->where(array('between',"$startime,$endtime"))->join('left join equipment as t2 on t2.equipment_pid = $id')->select();
		return $return;
	}
}
?>