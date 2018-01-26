<?php
class Util {
	private $servername = "localhost";
	private $username = "root";
	private $password = "root";
	private $dbname = "niming";
	private $conn;

	public function __construct() {
		// 创建连接
		$this -> conn = new mysqli($this -> servername, $this -> username, $this -> password, $this -> dbname);
		// 检测连接
		if ($this -> conn -> connect_error) {
			die("连接失败: " . $this -> conn -> connect_error);
		}
		$this -> conn->query("SET NAMES utf8");
	}

	public function getFieldBySql($sql) {
		$result = $this -> conn -> query($sql);
		if ($result -> num_rows > 0) {
			$row = $result -> fetch_assoc();
			$row['data'] = 1;
		} else {
			$row = array('data' => 0);
		}
		return $row;
	}

	public function addData($table,$values) {
		$key = implode(",", array_keys($values));
		$value = implode("','", array_values($values));
		$sql = "INSERT INTO ".$table." (".$key.") VALUES ('".$value."')";

		if ($this->conn -> query($sql) === TRUE) {
			$id = $this->conn->insert_id;
			$data = array('txtid'=>$id,'status'=>'ok');
			$sql = "INSERT INTO tb_zan (txtid,fid,flag) VALUES (".$id.",".$values['fid'].","."0)";
			if ($this->conn -> query($sql) !== TRUE) {
				return;
			}
		} else {
			$data = array('status'=>'error');
		}
		return $data;
	}
    
	public function getMessage($tid,$fid){
		$sql = "select id,txt from tb_txt where tid=".$tid;
		$result = $this -> conn -> query($sql);
		if ($result -> num_rows > 0) {
			$data = array();
			while($row = $result -> fetch_assoc()){
				$row['num']=$this->getHeartNum($row['id']);
				$row['flag']=$this->getHeartFlag($row['id'],$fid)['flag'];
				$data[] = $row;
			}
		} else {
			$data = array('data' => 0);
		}
		return $data;
	}
	
	public function getHeartNum($txtid){
		$sql = "select count(*) as num from tb_zan where txtid=".$txtid." and flag=1";
		$result = $this -> conn -> query($sql);
		if ($result -> num_rows > 0) {
			$row = $result -> fetch_assoc();
		} else {
			$row = array('num' => 0);
		}
		return $row['num'];
	}
	
	public function getHeartFlag($txtid,$fid){
		$sql = "select flag from tb_zan where txtid=".$txtid." and fid=".$fid;
		$result = $this -> conn -> query($sql);
		if ($result -> num_rows > 0) {
			$row = $result -> fetch_assoc();
			$row['count']=1;
		} else {
			$row = array('flag' => 0,'count'=>0);
		}
		return $row;
	}
	
	public function updateZan($id,$fid){
		$count = $this->getHeartFlag($id,$fid)['count'];
		$data =array();
		//给别人点赞
		if($count==0){
			$sql = "INSERT INTO tb_zan (txtid,fid,flag) VALUES (".$id.",".$fid.","."1)";
			if ($this->conn -> query($sql) === TRUE) {
				$data['flag']= 1;
			    $data['num']= $this->getHeartNum($id);
			}else{
				return;
			}
		}else{
			$flag = $this->getHeartFlag($id,$fid)['flag'];
			if($flag==0)
		        $flag=1;
		    else
		        $flag=0;
		    $sql = "update tb_zan  set flag=".$flag." where txtid=".$id." and fid=".$fid;
		    $result = $this -> conn -> query($sql);
		    if (!$result) {
			    $data['status']='error';
		     } else {
			     $data['flag']= $flag;
			     $data['num']= $this->getHeartNum($id);
		     }
		}
		
		return $data;
	}
	
	private function close() {
		$this -> conn -> close();
	}

}
