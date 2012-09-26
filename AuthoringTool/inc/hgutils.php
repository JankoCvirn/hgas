<?php 
require_once("php/Sql.php");

//TODO
//1.method to save main guide data
//2.method to save info points
//3.method to save nav points

class hgutils
{
	
	public $result;
	private $oSql;
	private $sSql;
	private $row;
	
	private $db_guide="h7993hga_hg-auth";
	private $tbl_guide="track";
	private $tbl_nav="navspot";
	private $tbl_info="infospot";
	public $uid;
	
	//get user id
	public function __construct($uid){
		$this->uid=$uid;
		
	}
	
	//check if guide name allready exists
	public function getGuideName($name){
		
		$this->oSql=new Sql($this->db_guide);
		$this->oSql->setErrorhandling(true, true);
		$this->sSql="SELECT * FROM  track where name='".$name."' ";
		
		$found=false;
		$this->oSql->q($this->sSql);
		if (($this->row=$this->oSql->fa())!=null){
		
			$this->uid=$this->row['name'];
				
				
			$found=true;
		}
		
		
			
		return $found;
		
		
	}
	
	//save main guide data
	/*$name=$_POST[''];
	$subName=$_POST[''];
	$wkt=$_POST[''];
	$summary=$_POST[''];
	$description=$_POST[''];
	$mapName=$_POST[''];
	$trackName=$_POST[''];*/
	
	public function setGuideData($name,$subName,$wkt,$summary,$description,$mapName,$trackName){
		
		$creation_time=time();
		$this->oSql=new Sql($this->db_guide);
		$this->oSql->setErrorhandling(true, true);
		$this->sSql="INSERT INTO '".$this->tbl_guide."' (name,sub_name,summary,map_name,
		                                                 track_name,description,
		                                                 wkt,created,uid)
					 VALUES('".$name."','".$subName."','".$summary."','".$mapName."',
		                    '".$trackName."','".$description."',
							'".$wkt."','".$creation_time."','".$this->uid."'
							) ";
		
		
		$this->oSql->q($this->sSql);
		
	}
	
	//save info point
	
	public function setInfoPoint(){
		
	}
	
	//save nav point
	
	public function setNavPoint(){
		
		
	}
	
	//save proxy point
	
	public function setProxyPoint(){
		
		
		
	}
}


?>