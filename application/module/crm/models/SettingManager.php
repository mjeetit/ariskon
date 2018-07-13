<?php
    class Crm_Model_SettingManager extends Zend_Custom
    {
		/**
		*Variable Holds the Name of Section Table
		**/
		/**
		**/
	
		public $parent_id = 1;				// For Getting submodule of application
		public $status    = '1';			// Status of module
		public $_getData  = array();			// Lavel of module.
	
		
		public function AddMasterSetting(){
		  if(!empty($this->_getData['1'])){
			       $this->_db->insert('company',array('company_name'=>$this->_getData['company_name'],'company_address'=>$this->_getData['company_address']));
				return 'company';   
		    }		   
			if(!empty($this->_getData['2'])){
			        $this->_db->insert('bussiness_unit',array_filter(array('bunit_name'=>$this->_getData['bunit_name'])));
				return 'businesunit';  
			 }		
			if(!empty($this->_getData['3'])){
			        $this->_db->insert('company_country',array_filter(array('country_id'=>$this->_getData['country_id'])));
				return 'country';  
			  }		
			if(!empty($this->_getData['4'])){
			       $this->_db->insert('zone',array_filter(array('bunit_id'=>$this->_getData['bunit_id'],
														 'zone_name'=>$this->_getData['zone_name'])));
				return 'zone';  
			}
			if(!empty($this->_getData['5'])){
			        $this->_db->insert('region',array_filter(array('zone_id'=>$this->_getData['zone_id'],
														   'bunit_id'=>$this->_getData['bunit_id'],
														   'region_name'=>$this->_getData['region_name'])));
				return 'region';  
			}
			if(!empty($this->_getData['6'])){
			         $this->_db->insert('area',array_filter(array('zone_id'=>$this->_getData['zone_id'],
														   'bunit_id'=>$this->_getData['bunit_id'],
														   'region_id'=>$this->_getData['region_id'],
														   'area_code'=>$this->_getData['area_code'],
														   'area_name'=>$this->_getData['area_name'])));
				return 'area';  
			}
			if(!empty($this->_getData['7'])){
			         $this->insertInToTable('headoffice', array($this->_getData));
			        /* $this->_db->insert('headoffice',array_filter(array('area_id'=>$this->_getData['area_id'],
														   'zone_id'=>$this->_getData['zone_id'],
														   'region_id'=>$this->_getData['region_id'],
														    'bunit_id'=>$this->_getData['bunit_id'],
															 'headoffice_address'=>$this->_getData['headoffice_address'])));*/
				return 'headoffice';  
			}
		 if(!empty($this->_getData['8'])){
			         $this->insertInToTable('headquater', array($this->_getData));
				return 'headquater';  
		 }
		 if(!empty($this->_getData['9'])){
			         $this->insertInToTable('city', array($this->_getData));
				return 'city';  
		 }
		  if(!empty($this->_getData['10'])){
			         $this->insertInToTable('street', array($this->_getData));
				return 'street';  
			}	
		}
		public function EditMasterSetting(){
		   if(!empty($this->_getData['1'])){
			       $this->_db->update('company',array('company_name'=>$this->_getData['company_name'],'company_address'=>$this->_getData['company_address']),
			 					"company_code='".$this->_getData['company_code']."'");
				return 'company';   
		    }		   
			if(!empty($this->_getData['2'])){
			        $this->_db->update('bussiness_unit',array_filter(array('bunit_name'=>$this->_getData['bunit_name'])),"bunit_id='".$this->_getData['bunit_id']."'");
				return 'businesunit';  
			 }		
			if(!empty($this->_getData['3'])){
			       $this->_db->update('company_country',array_filter(array('country_id'=>$this->_getData['country_id'])),"id='".$this->_getData['id']."'");
				return 'country';  
			  }		
			if(!empty($this->_getData['4'])){
			      $this->_db->update('zone',array_filter(array('bunit_id'=>$this->_getData['bunit_id'], 'zone_name'=>$this->_getData['zone_name'])),"zone_id='".$this->_getData['zone_id']."'");
				return 'zone';  
			}
			if(!empty($this->_getData['5'])){
			      $this->_db->update('region',array_filter(array('zone_id'=>$this->_getData['zone_id'],
														   'bunit_id'=>$this->_getData['bunit_id'],
														   'region_name'=>$this->_getData['region_name'])),"region_id='".$this->_getData['region_id']."'");
				return 'region';    
			}
		 if(!empty($this->_getData['6'])){
			      $this->_db->update('area',array_filter(array('zone_id'=>$this->_getData['zone_id'],
														   'bunit_id'=>$this->_getData['bunit_id'],
														   'region_id'=>$this->_getData['region_id'],
														   'area_code'=>$this->_getData['area_code'],
														   'area_name'=>$this->_getData['area_name'])),"area_id='".$this->_getData['area_id']."'");
				return 'area';    
			}
			if(!empty($this->_getData['7'])){
			         $this->_db->update('headoffice',array_filter(array('area_id'=>$this->_getData['area_id'],
														   'zone_id'=>$this->_getData['zone_id'],
														   'region_id'=>$this->_getData['region_id'],
														    'bunit_id'=>$this->_getData['bunit_id'],
															 'office_name'=>$this->_getData['office_name'],
															 'headoffice_address'=>$this->_getData['headoffice_address'])),"headoff_id='".$this->_getData['headoff_id']."'");
				return 'headoffice';  
			}
			if(!empty($this->_getData['8'])){
			         $this->_db->update('headquater',array_filter(array('area_id'=>$this->_getData['area_id'],
														   'zone_id'=>$this->_getData['zone_id'],
														   'region_id'=>$this->_getData['region_id'],
														   'bunit_id'=>$this->_getData['bunit_id'],
														   'headquater_name'=>$this->_getData['headquater_name'],
														   'headquater_address'=>$this->_getData['headquater_address'])),"headquater_id='".$this->_getData['headquater_id']."'");
				return 'headquater';  
			}
			if(!empty($this->_getData['9'])){
			         $this->_db->update('city',array_filter(array('headquater_id'=>$this->_getData['headquater_id'],
					 									   'area_id'=>$this->_getData['area_id'],
														   'zone_id'=>$this->_getData['zone_id'],
														   'region_id'=>$this->_getData['region_id'],
														   'bunit_id'=>$this->_getData['bunit_id'],
														   'city_name'=>$this->_getData['city_name'])),"city_id='".$this->_getData['city_id']."'");
				return 'city';  
			}
		   if(!empty($this->_getData['10'])){
			         $this->_db->update('street',array_filter(array('city_id'=>$this->_getData['city_id'],
					 										'headquater_id'=>$this->_getData['headquater_id'],
					 										'area_id'=>$this->_getData['area_id'],
														   'zone_id'=>$this->_getData['zone_id'],
														   'region_id'=>$this->_getData['region_id'],
														    'bunit_id'=>$this->_getData['bunit_id'],
															 'street_name'=>$this->_getData['street_name'])),"street_id='".$this->_getData['street_id']."'");
				return 'street';  
			}	
		}
		public function getZone(){
		 $select = $this->_db->select()
		 				->from(array('ZT'=>'zone'),array('*'))
						->joininner(array('BU'=>'bussiness_unit'),"ZT.bunit_id=BU.bunit_id",array('bunit_name'));
						//->joininner(array('B2C'=>'business_to_country'),"B2C.bunit_id=BU.bunit_id",array())
						//->joininner(array('CT'=>'country'),"CT.country_id=B2C.country_id",array('country_name'))
						//echo $select->__toString();die;
		     $result = $this->getAdapter()->fetchAll($select);
			return $result; 
		
		}
		
		public function getCompanyCountry(){
		    $select = $this->_db->select()
						->from(array('CC'=>'company_country'),array('country_id','id'))
						->joinleft(array('CT'=>'country'),"CT.country_id=CC.country_id",array('country_name'));
		     $result = $this->getAdapter()->fetchAll($select);
			return $result;  			
		}
		public function getRegion(){
		 $select = $this->_db->select()
		 				->from(array('RT'=>'region'),array('*'))
		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=RT.zone_id",array('zone_name'))
						->joininner(array('BU'=>'bussiness_unit'),"RT.bunit_id=BU.bunit_id",array('bunit_name'));
						//echo $select->__toString();die;
		     $result = $this->getAdapter()->fetchAll($select);
			return $result; 
		
		}
		public function getArea(){
		 $select = $this->_db->select()
		                ->from(array('AT'=>'area'),array('*'))
		 				->joininner(array('RT'=>'region'),"RT.region_id=AT.region_id",array('region_name'))
		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=AT.zone_id",array('zone_name'))
						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=AT.bunit_id",array('bunit_name'));
						//echo $select->__toString();die;
		     $result = $this->getAdapter()->fetchAll($select);
			return $result; 
		
		}
		public function getHeadOffice(){
		$data = array();
			 $select = $this->_db->select()
							->from(array('HO'=>'headoffice'),array('*'))
							->joininner(array('AT'=>'area'),"AT.area_id=HO.area_id",array('area_name'))
							->joininner(array('CT'=>'city'),"CT.city_id=HO.city_id",array('city_name'))
							->joininner(array('RT'=>'region'),"RT.region_id=HO.region_id",array('region_name'))
							->joininner(array('ZT'=>'zone'),"ZT.zone_id=HO.zone_id",array('zone_name'))
							->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=HO.bunit_id",array('bunit_name'));
							//echo $select->__toString();die;
				 $data = $this->getAdapter()->fetchAll($select);
			return $data; 
		
		}
	  public function getHeadQuaters(){
	    $select = $this->_db->select()
		 				->from(array('HQ'=>'headquater'),array('*'))
		 				->joininner(array('AT'=>'area'),"AT.area_id=HQ.area_id",array('area_name'))
						->joininner(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('region_name'))
		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('zone_name'))
						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=HQ.bunit_id",array('bunit_name'));
						//echo $select->__toString();die;
		     $result = $this->getAdapter()->fetchAll($select);
			return $result;
	  }	
	  public function getCity(){
		 $select = $this->_db->select()
		 				->from(array('CT'=>'city'),array('*'))
						->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=CT.headquater_id",array('headquater_name'))
		 				->joininner(array('AT'=>'area'),"AT.area_id=CT.area_id",array('area_name'))
						->joininner(array('RT'=>'region'),"RT.region_id=CT.region_id",array('region_name'))
		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=CT.zone_id",array('zone_name'))
						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=CT.bunit_id",array('bunit_name'));
						//echo $select->__toString();die;
		     $result = $this->getAdapter()->fetchAll($select);
			return $result; 
	 }
	 public function getStreet(){
		 $select = $this->_db->select()
		 				->from(array('ST'=>'street'),array('*'))
						->joininner(array('CT'=>'city'),"CT.city_id=ST.city_id",array('city_name'))
						->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=ST.headquater_id",array('headquater_name'))
		 				->joininner(array('AT'=>'area'),"AT.area_id=ST.area_id",array('area_name'))
						->joininner(array('RT'=>'region'),"RT.region_id=ST.region_id",array('region_name'))
		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=ST.zone_id",array('zone_name'))
						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=ST.bunit_id",array('bunit_name'));
						//echo $select->__toString();die;
		     $result = $this->getAdapter()->fetchAll($select);
			return $result; 
		
	}
		
	   public function getEdit(){
	       switch($this->_getData['level']){
		       case 1:
			       return $this->getCompanyById();
			   break;
			   case 2:
			   	    return $this->getBussinessUnitById();
			   break;
			   case 3:
			   	    return $this->getCountryById();
			   break;
			    case 4:
			   	    return $this->getZoneById();
			   break;
			   case 5:
			   	    return $this->getRegionById();
			   break;
			   case 6:
			   	    return $this->getAreaById();
			   break;
			    case 7:
			   	    return $this->getHeadQuaterById();
			   case 8:
			   	    return $this->getheadquaterOfCity();	
			   break;
			   case 9:
			   	    return $this->getCityById();	
			   break;
			   case 10:
			   	    return $this->getStreetById();	
			   break; 		   
		   }
	   }
	  public function getCompanyById(){
	         $select = $this->_db->select()
						->from('company',array('*'))
						->where("company_code='".$this->_getData['company_code']."'");
		     $result = $this->getAdapter()->fetchRow($select);
			return $result;
	  }
	  public function getBussinessUnitById(){
	        $select = $this->_db->select()
						->from('bussiness_unit',array('*'))
						->where("bunit_id='".$this->_getData['bunit_id']."'");
		     $result = $this->getAdapter()->fetchRow($select);
			return $result;
	  }
	  public function getCountryById(){
	        $select = $this->_db->select()
						->from('company_country',array('*'))
						->where("id='".$this->_getData['id']."'");
		     $result = $this->getAdapter()->fetchRow($select);
			return $result;
	  }
	  public function getZoneById(){
	        $select = $this->_db->select()
						->from('zone',array('*'))
						->where("zone_id='".$this->_getData['zone_id']."'");
		     $result = $this->getAdapter()->fetchRow($select);
			return $result;
	  }
	 public function getRegionById(){
	     $select = $this->_db->select()
						->from('region',array('*'))
						->where("region_id='".$this->_getData['region_id']."'");
		 $result = $this->getAdapter()->fetchRow($select);
		return $result;
	  }
	  public function getAreaById(){
	     $select = $this->_db->select()
						->from('area',array('*'))
						->where("area_id='".$this->_getData['area_id']."'");
		 $result = $this->getAdapter()->fetchRow($select);
		return $result;
	  } 
	  public function getHeadQuaterById(){
	     $select = $this->_db->select()
						->from('headoffice',array('*'))
						->where("headoff_id='".$this->_getData['headoff_id']."'");
		 $result = $this->getAdapter()->fetchRow($select);
		return $result;
	  }
	 public function getheadquaterOfCity(){
	    $select = $this->_db->select()
						->from('headquater',array('*'))
						->where("headquater_id='".$this->_getData['headquater_id']."'");
		 $result = $this->getAdapter()->fetchRow($select);
		return $result;
	 } 
	 public function getCityById(){
	      $select = $this->_db->select()
						->from('city',array('*'))
						->where("city_id='".$this->_getData['city_id']."'");
		 $result = $this->getAdapter()->fetchRow($select);
		return $result;
	 } 
	 public function getStreetById(){
	      $select = $this->_db->select()
						->from('street',array('*'))
						->where("street_id='".$this->_getData['street_id']."'");
		 $result = $this->getAdapter()->fetchRow($select);
		return $result;
	 }  
	 public function BackAction(){
	   switch($this->_getData['level']){
	     case 1:
		    return 'company';
			break;
		 case 2:
		   return 'businesunit';
		   break;
		 case 3:
		   return 'country';
		   break;
	    case 4:
	     return 'zone';
	     break;	
		  case 5:
	     return 'region';
	     break;
		  case 6:
	     return 'area';
	     break;
		case 7:
	     return 'headoffice';
	     break;
		case 8:
	     return 'headquater';
	     break;
		 case 9:
	     return 'city';
	     break;
		 case 10:
	     return 'street';
	     break;  
	   }
	 } 
  public function AddDesignation(){
	    $this->_db->insert('designation',array_filter(array('designation_name'=>$this->_getData['designation_name'],'designation_code'=>$this->_getData['designation_code'],'designation_level'=>$this->_getData['designation_level'])));
	 }
  public function AddDepartment(){
	    $this->_db->insert('department',array_filter(array('department_name'=>$this->_getData['department_name'])));
	 }
    public function AddSalaryhead(){
	  /* if(!empty($this->_getData['Detectsalaryhead'])){
	      $this->_db->insert('salary_head',array_filter(array('salary_title'=>$this->_getData['salary_title'],'salary_type'=>'2')));
	   }else{
	      $this->_db->insert('salary_head',array_filter(array('salary_title'=>$this->_getData['salary_title'])));
	   }*/
	   $this->_db->insert('salary_head',array_filter(array('salary_title'=>$this->_getData['salary_title'],'salary_type'=>$this->_getData['salary_type'],'prodata_status'=>$this->_getData['prodata_status'],'sequence'=>$this->_getData['sequence'])));
	 }
  
	 
   public function getEditNew(){
      switch($this->_getData['type']){
	     case 'Designation':
		    return $this->getDesignationByID();
			break;
		 case 'Department':
		    return $this->getDepartmentByID();
		   break;
		 case 'Salaryhead':
		   return $this->getSalaryheadByID();
		   break;	
	   }
   }
    public function getDesignationByID(){
	    $select = $this->_db->select()
						->from('designation',array('*'))
						->where("designation_id='".$this->_getData['designation_id']."'");
						//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchRow($select);
		return $result;
	 }
   public function getDepartmentByID(){
	    $select = $this->_db->select()
						->from('department',array('*'))
						->where("department_id='".$this->_getData['department_id']."'");
		$result = $this->getAdapter()->fetchRow($select);
		return $result;
	 }
	public function getSalaryheadByID(){
	    $select = $this->_db->select()
						->from('salary_head',array('*'))
						->where("salaryhead_id='".$this->_getData['salaryhead_id']."'");
		$result = $this->getAdapter()->fetchRow($select);
		return $result;
	 }	
	public function BackNewAction(){
	   switch($this->_getData['type']){
	      case 'Designation':
		    return 'designation';
			break;
		  case 'Department':
		   return 'department';
		   break;
		 case 'Salaryhead':
		   return 'salaryhead';
		   break;	
	   }
	 }
  public function EditDesignation(){
	    $this->_db->update('designation',array('designation_name'=>$this->_getData['designation_name'],'designation_code'=>$this->_getData['designation_code'],'designation_level'=>$this->_getData['designation_level']),"designation_id='".$this->_getData['designation_id']."'");
	 }
  public function EditDepartment(){
	    $this->_db->update('department',array('department_name'=>$this->_getData['department_name']),"department_id='".$this->_getData['department_id']."'");
	 } 
  public function EditSalaryhead(){
	    $this->_db->update('salary_head',array('salary_title'=>$this->_getData['salary_title'],'salary_type'=>$this->_getData['salary_type'],'prodata_status'=>$this->_getData['prodata_status'],'sequence'=>$this->_getData['sequence']),"salaryhead_id='".$this->_getData['salaryhead_id']."'");
	 }
  public function getCountry(){
         $select = $this->_db->select()
						->from('country',array('*'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
  }
  public function AddSalaryTemplate(){
	 if(!empty($this->_getData['salaryhead_id'])){
	    $addhead = implode(',',$this->_getData['salaryhead_id']);
	  }
	   if(!empty($this->_getData['detsalaryhead_id'])){
	      $detecthead = implode(',',$this->_getData['detsalaryhead_id']);
	  }
		 $this->_db->insert('salary_template',array_filter(array('bunit_id'=>$this->_getData['bunit_id'],
													'department_id'=>$this->_getData['department_id'],
													'designation_id'=>$this->_getData['designation_id'],
													'salaryhead_id'=>$addhead,
													'detsalaryhead_id'=>$detecthead)));
		 return $this->getAdapter()->lastInsertId(); //true;											
	
   }
   public function getTemplate(){
     $select = $this->_db->select()
						->from(array('ST'=>'salary_template'),array('*'))
						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=ST.bunit_id",array('bunit_name'))
						->joininner(array('DP'=>'department'),"DP.department_id=ST.department_id",array('department_name'))
						->joininner(array('DT'=>'designation'),"DT.designation_id=ST.designation_id",array('designation_name'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
   }
   public function getTemplatehead($template_id){
       $select = $this->_db->select()
						->from('salary_template',array('*'))
						->where("salary_template_id='".$template_id."'");
		$result = $this->getAdapter()->fetchRow($select);
		return $result;
   }
   public function AddSalaryTemplateAmount(){
     foreach($this->_getData['salaryhead_id'] as $key=>$salaryhead){
	      $this->_db->insert('salary_template_amount',array_filter(array('salary_template_id'=>$this->_getData['salary_template_id'],
													'salaryhead_id'=>$salaryhead,
													'amount'=>$this->_getData['amount'][$key]))); 
	 }
   }
   public function getTemplateRecordById(){
      $select = $this->_db->select()
						->from(array('ST'=>'salary_template'),array('*'))
						->where("ST.salary_template_id='".$this->_getData['salary_template_id']."'");
	 $result = $this->getAdapter()->fetchRow($select);
     return $result;					
   }
   public function getTemplateAmountById(){
      $select = $this->_db->select()
						->from(array('STA'=>'salary_template_amount'),array('*'))
						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=STA.salaryhead_id",array('salary_type'))
						->where("STA.salary_template_id='".$this->_getData['salary_template_id']."'");
	 $results = $this->getAdapter()->fetchAll($select);
		 foreach($results as $result){
		     $amount[$result['salaryhead_id']] = $result['amount'];
		 }
     return $amount;	 
   }
   public function editSalaryTemplate(){
       if(!empty($this->_getData['salaryhead_id'])){
	    $addhead = implode(',',$this->_getData['salaryhead_id']);
	  }
	   if(!empty($this->_getData['detsalaryhead_id'])){
	      $detecthead = implode(',',$this->_getData['detsalaryhead_id']);
	  }
		 $this->_db->update('salary_template',array_filter(array('bunit_id'=>$this->_getData['bunit_id'],
													'department_id'=>$this->_getData['department_id'],
													'designation_id'=>$this->_getData['designation_id'],
													'salaryhead_id'=>$addhead,
													'detsalaryhead_id'=>$detecthead)),"salary_template_id='".$this->_getData['salary_template_id']."'");
   }
   public function editSalaryTemplateAmount(){
      $this->_db->delete('salary_template_amount',"salary_template_id='".$this->_getData['salary_template_id']."'");
      foreach($this->_getData['salaryhead_id'] as $key=>$salaryhead){
	      $this->_db->insert('salary_template_amount',array_filter(array('salary_template_id'=>$this->_getData['salary_template_id'],
													'salaryhead_id'=>$salaryhead,
													'amount'=>$this->_getData['amount'][$key]))); 
	 }
   }	
  
	/*public function getProvidentSettings($cond=false){
	   $where = ''; 
	   if($cond){
	      $where = " AND setting_id='".$cond."'";
	   }
	   $select = $this->_db->select()
	   							 ->from(array('PS'=>'provident_setting'),array('*'))
								 ->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PS.bunit_id",array('bunit_name'))
								 ->joininner(array('DT'=>'department'),"DT.department_id=PS.department_id",array('department_name'))
								 ->joininner(array('DG'=>'designation'),"DG.designation_id=PS.designation_id",array('designation_name'))
								 ->where("1".$where);
		$results = $this->getAdapter()->fetchAll($select);
		return $results;
	}*/
	
   public function updateprovidentSettings(){
      $this->_db->update('provident_setting',array('provident_type'=>$this->_getData['provident_type'],
	  											   'provident_value'=>$this->_getData['provident_value'],
												   'provident_by_company'=>$this->_getData['provident_by_company'],
												   'comapny_provident_value'=>$this->_getData['comapny_provident_value'],
												   'provident_on'=>$this->_getData['provident_on'],
												   'extra_provident_on'=>$this->_getData['extra_provident_on'],
												   'provident_status'=>$this->_getData['provident_status']));
	  $_SESSION[SUCCESS_MSG] = 'Settings Updated SuccessFully';
   }	
   public function UpdateSalaryDuration(){
       $this->_db->update('salary_duration',array('from_date'=>$this->_getData['from_date'],'to_date'=>$this->_getData['to_date']));
   }
   
   public function UpdateProvidentSetting(){
      
   } 
   public function getAllSalaryHead(){
	    $select = $this->_db->select()
						->from('salary_head',array('*'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	 }
   public function getExpenseSetting(){
      /* $column  = array('BU.bunit_name','DP.department_name','DT.designation_name',new Zend_Db_Expr('CASE EXP.expense_type WHEN 1 THEN "Actual" WHEN 2 THEN "Fixed" ELSE "Actual+Fixed" END Type'),'EXP.amount','EXP.mobile_bill','ADT.designation_name as Approved Designation','EXP.exp_setting_id');*/
	   
	   $column  = array('BU.bunit_name','DP.department_name','DT.designation_name','EXP.number_of_approval','EXP.exp_setting_id','(SELECT SUM(ETA.expense_amount) FROM expense_template_amount AS ETA WHERE ETA.exp_setting_id=EXP.exp_setting_id) as Expense Amount');
       $select = $this->_db->select()
						->from(array('EXP'=>'expense_setting'),$column)
						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=EXP.bunit_id",array())
						->joininner(array('DP'=>'department'),"DP.department_id=EXP.department_id",array())
						->joininner(array('DT'=>'designation'),"DT.designation_id=EXP.designation_id",array());
						//->joininner(array('ET'=>'expense_template_amount'),"ET.desigexp_setting_idnation_id=EXP.exp_setting_id",array());
						//->joinleft(array('ADT'=>'designation'),"ADT.designation_id=EXP.approved_designation",array());
						//print_r($select->__toString());die;
		return $select;				
   }
   public function getExpenseSettingById(){
       $select = $this->_db->select()
						->from(array('EXP'=>'expense_setting'),array('*'))
						->where("exp_setting_id='".$this->_getData['exp_setting_id']."'"); 
	   $result = $this->getAdapter()->fetchRow($select);
	  return $result;
   }
   public function AddUpdateExpenseSetting(){//print_r($this->_getData);die;
      if($this->_getData['Mode']=='Add'){
	       $setting_id = $this->insertInToTable('expense_setting', array($this->_getData));
		     if(!empty($this->_getData['expense_amount'])){
			     foreach($this->_getData['expense_amount'] as $key=>$expenseamount){
				     $this->_db->insert('expense_template_amount',array('exp_setting_id'=>$setting_id,'head_id'=>$this->_getData['head_id'][$key],'expense_amount'=>$expenseamount));
				 }
			  }
		   $_SESSION[SUCCESS_MSG] = "Setting has been Added Successfully";
	  }else{
	       $this->updateTable('expense_setting',$this->_getData,array("exp_setting_id"=>$this->_getData['exp_setting_id']));
		   $this->_db->delete('expense_template_amount',"exp_setting_id='".$this->_getData['exp_setting_id']."'");
		   if(!empty($this->_getData['expense_amount'])){
			     foreach($this->_getData['expense_amount'] as $key=>$expenseamount){
				     $this->_db->insert('expense_template_amount',array('exp_setting_id'=>$this->_getData['exp_setting_id'],'head_id'=>$this->_getData['head_id'][$key],'expense_amount'=>$expenseamount));
				 }
			  }
		   $_SESSION[SUCCESS_MSG] = "Setting has been Updated Successfully";
	  }
	   if($this->_getData['updateemp']=='on'){
		       $this->updateEmployeeExpense();
	   }
   }
   public function getExpenseHead(){
       $where = "1";
	    if(!empty($this->_getData['head_id'])){
		  $where .=" AND head_id='".$this->_getData['head_id']."'";
		}
        $select = $this->_db->select()
						->from(array('EH'=>'expense_head'),array('*'))
						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=EH.salary_head",array('salary_title'))
						->where($where);
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
   }
   public function AddUpdateHeadExpense(){
       if($this->_getData['Mode']=='Add'){
	      $this->insertInToTable('expense_head', array($this->_getData));
		  $_SESSION[SUCCESS_MSG] = "Setting has been Added Successfully";
	   }
	   if($this->_getData['Mode']=='Edit'){ 
	      $this->updateTable('expense_head',$this->_getData,array('head_id'=>$this->_getData['head_id']));
		  $_SESSION[SUCCESS_MSG] = "Setting has been Updated Successfully";
	   }
   }
   public function updateEmployeeExpense(){
      $select = $this->_db->select()
						->from(array('UD'=>'employee_personaldetail'),array('user_id'))
						->where("designation_id='".$this->_getData['designation_id']."' AND department_id='".$this->_getData['department_id']."' AND delete_status='0'");
	  $result = $this->getAdapter()->fetchAll($select);//print_r($result);die;
	  foreach($result as $users){
	     if(!empty($this->_getData['head_id'])){
		   $this->_db->delete('emp_expense_amount',"user_id='".$users['user_id']."'");
            foreach($this->_getData['expense_amount'] as $key=>$expenseamount){
			  $this->_db->insert('emp_expense_amount',array_filter(array('user_id'=>$users['user_id'],'head_id'=>$this->_getData['head_id'][$key],'expense_amount'=>$expenseamount)));
			  			  
			}
		}
	  }
    }
	
	public function AddUpdateesiSetting(){
	    $detail = $this->EsiDetail();
		if(!empty($detail)){
		   $this->_db->update('esi_setting',array('esi_type'	=>	$this->_getData['esi_type'],
		   										  'esi_value'	=> 	$this->_getData['esi_value'],
												  'esi_by_company'=>$this->_getData['esi_by_company'],
												  'comapny_esi_value'=>$this->_getData['comapny_esi_value'],
												  'esi_on'		=>	$this->_getData['esi_on'],
												  'extra_esi_on'=>	$this->_getData['extra_esi_on'],
												  'esi_status'	=>	$this->_getData['esi_status']));
		}else{
		    $this->_db->insert('esi_setting',array_filter(array('esi_type'=>$this->_getData['esi_type'],
																'esi_value'=>$this->_getData['esi_value'],
																'esi_by_company'=>$this->_getData['esi_by_company'],
																'comapny_esi_value'=>$this->_getData['comapny_esi_value'],
																'esi_on'=>$this->_getData['esi_on'],
																'extra_esi_on'=>$this->_getData['extra_esi_on'],
																'esi_status'=>$this->_getData['esi_status'])));
		}
	}
	public function EsiDetail(){
	   $select = $this->_db->select()
						->from(array('ES'=>'esi_setting'),array('*'));
	  $result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
	  return $result;
	}
}
?>