<?php
class InvoicesController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		/*if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }*/
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 6;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new InvoiceManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
    public function invoicelistAction(){
	   $this->view->Invoices = $this->ObjModel->getInvoiceList();
	}
	public function addinvoiceAction(){
	  if($this->_request->isPost()){
	     $this->ObjModel->insertInToTable('invoices',array($this->_data));
		 $this->_redirect('Invoices/invoicelist');
	  }
	  
	}
	public function editinvoiceAction(){
	   if($this->_request->isPost()){
	     $this->ObjModel->updateTable('invoices',$this->_data,array('invoice_id'=>$this->_data['invoice_id']));
		 $this->_redirect('Invoices/invoicelist');
	  }
	   $this->view->Invoices = $this->ObjModel->getInvoiceList();
	}
		public function uploadefileAction(){
	  if($this->_request->isPost()){
	    $this->ObjModel->uploadedata();
	  }
	}
}
?>
