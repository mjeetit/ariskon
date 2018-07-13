<?php
class LeaveManager extends Zend_Custom
{
	private $_leaveType 		= "leavetypes";
	private $_leavedistribution = "leavedistributions";
	private $_leaveapproval 	= "leaveapprovals";
	private $_designation 		= "designation";
	
	public function getLeaveTypes() {
		$select = $this->_db->select()->from($this->_leaveType,'*')->order('typeID','ASC');
		return $this->getAdapter()->fetchAll($select);
	}
	
	public function getLeaveByID($data=array()) {
		$typeID = (!empty($data['typeID'])) ? trim($data['typeID']) : 0;
		$select = $this->_db->select()->from($this->_leaveType,'*')->where('typeID='.$typeID);
		$result = $this->getAdapter()->fetchRow($select);
		return $result;  			
	}
	
	public function addLeaveType(){
		$this->_db->insert($this->_leaveType,array_filter(array('typeName'=>$this->_getData['typeName'],
			  													 'typeDesc'=>$this->_getData['typeDesc'],
																 'creditPeriod'=>$this->_getData['creditPeriod'],
																 'carryForward'=>$this->_getData['carryForward'],
																 'carryforward_number'=>$this->_getData['carryforward_number'],
																 'encashment_status'=>$this->_getData['encashment_status'],
																 'encashment_number'=>$this->_getData['encashment_number'])));
	}
	
	public function editLeaveType() {
		$this->_db->update($this->_leaveType,array('typeName'=>$this->_getData['typeName'],
													'typeDesc'=>$this->_getData['typeDesc'],
													'creditPeriod'=>$this->_getData['creditPeriod'],
													'carryForward'=>$this->_getData['carryForward'],
													'carryforward_number'=>$this->_getData['carryforward_number'],
													'encashment_status'=>$this->_getData['encashment_status'],
													'encashment_number'=>$this->_getData['encashment_number'],
													'status'=>$this->_getData['status']),'typeID='.$this->_getData['token']);
	}
	
	public function getLeaveDistribution() {
		$select = $this->_db->select()->from($this->_designation,array('designation_id','designation_name'))->order('designation_name','ASC');
		$result = $this->getAdapter()->fetchAll($select);
		$design = array();
		foreach($result as $key=>$desig) {
			$design[$key]['ID']    = $desig['designation_id'];
			$design[$key]['Name']  = $desig['designation_name'];
			$design[$key]['Leave'] = $this->getDesignationLeave(array('DesignID'=>$desig['designation_id']));
		}
		
		return $design;
	}
	
	public function getLeaveDistributionByID($data=array()) {
		$desigID = (!empty($data['desigID'])) ? trim($data['desigID']) : 0;
		$select = $this->_db->select()->from($this->_designation,array('designation_id','designation_name'))->where('designation_id='.$desigID);
		$result = $this->getAdapter()->fetchRow($select);
		$design['ID']    = $result['designation_id'];
		$design['Name']  = $result['designation_name'];
		$design['Leave'] = $this->getDesignationLeave(array('DesignID'=>$result['designation_id']));
		
		return $design;
	}
	
	public function updateLeaveDistribution($data) {
		//$desigID     = isset($data['token'])         ? Class_Encryption::decode($data['token']) : 0;
		$desigID = isset($data['token']) ? trim($data['token']) : 0;		
		
		$this->_db->delete($this->_leavedistribution,'designation_id='.$desigID);
		
		$allLeave = $this->getLeaveTypes();
		foreach($allLeave as $leave) {
			if(trim($data['type_'.$leave['typeID']]) >= 0) {
				$query = $this->_db->insert($this->_leavedistribution,array('designation_id'=>$desigID,'leave_type_id'=>$leave['typeID'],'leave_no'=>trim($data['type_'.$leave['typeID']]),'prob_leaveno'=>trim($data['prob_type_'.$leave['typeID']])));
			}
		}
		return true;
	}
	
	public function getDesignationLeave($data=array()) {
		$desigID = (isset($data['DesignID'])) ? trim($data['DesignID']) : 0;
		$select  = $this->_db->select()->from($this->_leavedistribution,array('leave_type_id','leave_no','prob_leaveno'))->where('designation_id=?',$desigID)->order('leave_type_id','ASC');
		$leaves  = array();
		foreach($this->getAdapter()->fetchAll($select) as $leave) {
			$leaves[$leave['leave_type_id']] = $leave['leave_no'];
			$leaves['Prob'][$leave['leave_type_id']] = $leave['prob_leaveno'];
		}
		
		return $leaves;
	}
	
	public function getLeaveApprovals() {
		$select = $this->_db->select()
							->from(array('DT'=>$this->_designation),array('*'))
							->joinleft(array('LA'=>$this->_leaveapproval),'LA.designation_id=DT.designation_id',array("LA.approval_no"))
							->order('DT.designation_name','ASC');
		return $this->getAdapter()->fetchAll($select);
	}
	
	public function getApprovalByID($data=array()) {
		$desigID = (!empty($data['desigID'])) ? trim($data['desigID']) : 0;
		$select = $this->_db->select()
							->from(array('DT'=>$this->_designation),array('*'))
							->joinleft(array('LA'=>$this->_leaveapproval),'LA.designation_id=DT.designation_id',array("LA.approval_no"))
							->where('DT.designation_id='.$desigID);
		$result = $this->getAdapter()->fetchRow($select);
		return $result;  			
	}
	
	public function updateLeaveApproval($data) {
		//$desigID     = isset($data['token'])         ? Class_Encryption::decode($data['token']) : 0;
		$desigID     = isset($data['token'])       ? trim($data['token']) : 0;
		$approval_no = isset($data['approval_no']) ? $data['approval_no'] : '';
		
		$select = $this->_db->select()->from($this->_leaveapproval,'*')->where('designation_id='.$desigID);
		$result = $this->getAdapter()->fetchAll($select);
		if(count($result)>0) {
			$query = $this->_db->update($this->_leaveapproval,array('approval_no'=>$approval_no),'designation_id='.$desigID);
		}
		else {
			$query = $this->_db->insert($this->_leaveapproval,array('designation_id'=>$desigID,'approval_no'=>$approval_no));
		}
		return true;
	}
}
?>