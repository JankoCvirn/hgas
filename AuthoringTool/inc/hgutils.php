<?php 
require_once("php/Sql.php");



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
	private $tbl_poi="poispot";
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
	
	
	
	public function setGuideData($name,$subName,$wkt,$summary,$tcolor,$bcolor,$distance,$region,$difficulty,$nature){
		
		$creation_time=time();
		$this->oSql=new Sql($this->db_guide);
		$this->oSql->setErrorhandling(true, true);
		$this->sSql="INSERT IGNORE INTO ".$this->tbl_guide." (name,sub_name,summary,wkt,created,uid,tcolor,bcolor,distance,region,difficulty,nature)
					 VALUES('".$name."','".$subName."','".$summary."','".$wkt."','".$creation_time."','".$this->uid."',
					        '".$tcolor."','".$bcolor."','".$distance."','".$region."','".$difficulty."','".$nature."'			 		
							) ";
		
		
		$this->oSql->q($this->sSql);
		
	}
	
	//save info point
	
	public function setInfoPoint($parent_id,$desc,$picture,$wkt,$oname){
		
		$creation_time=time();
		$this->oSql=new Sql($this->db_guide);
		$this->oSql->setErrorhandling(true, true);
		$this->sSql="INSERT  INTO ".$this->tbl_info." (parent_id,description,picture,wkt,created,uid,name)
		VALUES('".$parent_id."','".$desc."','".$picture."','".$wkt."','".$creation_time."','".$this->uid."','".$oname."'
		) ";
		
		
		$this->oSql->q($this->sSql);
		
	}
	
	//save nav point
	
	public function setNavPoint($parent_id,$desc,$picture,$wkt,$oname){
		
		$creation_time=time();
		$this->oSql=new Sql($this->db_guide);
		$this->oSql->setErrorhandling(true, true);
		$this->sSql="INSERT  INTO ".$this->tbl_nav." (parent_id,description,picture,wkt,created,uid,name)
		VALUES('".$parent_id."','".$desc."','".$picture."','".$wkt."','".$creation_time."','".$this->uid."','".$oname."'
		) ";
		$this->oSql->q($this->sSql);
		
	}
	
	//save proxy point
	
	public function setProxyPoint($parent_id,$desc,$picture,$wkt,$prox,$oname){
		
		$creation_time=time();
		$this->oSql=new Sql($this->db_guide);
		$this->oSql->setErrorhandling(true, true);
		$this->sSql="INSERT  INTO ".$this->tbl_poi." (parent_id,description,picture,wkt,created,uid,proxalert,name)
		VALUES('".$parent_id."','".$desc."','".$picture."','".$wkt."','".$creation_time."','".$this->uid."','".$prox."','".$oname."'
		) ";
		
		
		$this->oSql->q($this->sSql);
		
	}
}


?>