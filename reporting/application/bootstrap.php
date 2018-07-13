<?php
$root = dirname(dirname(__FILE__));
require_once './library/Zend/Loader.php';

class Bootstrap
{
    public static $frontController = null;
    public static $root = '';
    //public static $baseUrl = 'http://ariskon.jclifecare.com/reporting/';
    public static $baseUrl = 'http://localhost/ariskon/reporting/';
    public static $registry = null;
	public static $setPrivillages = NULL;
	public static $Translation = array();
	public static $tr = NULL;
	public static $fckpath = NULL;
	public static $dbObj = NULL;
	public static $LabelObj = NULL;
	public static $Mail = NULL;
	public static $_parent = 0;
	public static $_level = 1;
	public static $ActionName = 'index';
	
    //Primary function will be called first from index.php, it will call rest of functions to boot
    public static function run()
    {
        self::prepare();
        $response = self::$frontController->dispatch();
        self::sendResponse($response);
    }

    /**
     * It is used to prepare from macro view.
     * It gives macro view idea about preparation.
     * Sequence of each call is important.
     */
    public static function prepare()
    {
        self::setupDateTime();
		self::setupPath();
    	self::setupErrorReporting();

	//To load all classes automatically, without include or require statement
        Zend_Loader::registerAutoload();
        self::setupRegistry();
        self::setupConfiguration();
        self::setupFrontController();
        self::setupView();
		self::setupDatabase();
		self::setupSession();
		self::commonClass();
   }

    /**
     * Library path setting.
     * Application path setting.
     * Model path setting.
     * Forms path setting.
     */
    public static function setupPath()
    {
        $root = dirname(dirname(__FILE__));
		set_include_path(
		    $root . '/library' . PATH_SEPARATOR .
                    $root . '/application' . PATH_SEPARATOR .
                    $root . '/application/models' . PATH_SEPARATOR .
                    $root . '/application/forms' . PATH_SEPARATOR .
                    get_include_path()
		);
        self::$root = dirname(dirname(__FILE__));
    }

    /**
     * Error reporting setting
     * @return Error
     */
    public static function setupErrorReporting()
    {
    	//error_reporting(E_ALL|E_STRICT);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
    	ini_set('display_errors', 1);
    }
	
    /**
     * Date Time setting
     * @return Date Time
     */
    public static function setupDateTime()
    {
	date_default_timezone_set('Asia/Calcutta');  
    }

    /**
     * Registry setting
     */
    public static function setupRegistry()
    {
        self::$registry = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
        Zend_Registry::setInstance(self::$registry);
    }

    /**
     * Initialize the configuration
     * @return void
     */
    public static function setupConfiguration()
    {
        $config = new Zend_Config_Ini(
            self::$root . '/configuration/config.ini',
            'main'
        );
        self::$registry->configuration = $config;
    }

    /**
     * Important: Setting of Default controller.
     */
    public static function setupFrontController()
    {
	self::$frontController = Zend_Controller_Front::getInstance();
	self::$frontController->setDefaultControllerName('Admin');
        self::$frontController->throwExceptions(true);
        self::$frontController->returnResponse(true);
        self::$frontController->setControllerDirectory(
                array(
                    'default' => self::$root . '/application/controllers'
		)
        );
	
        self::$frontController->setParam('registry', self::$registry);
    }

    /**
     * Initialize the view
     * @return Zend_View
     */
    public static function setupView()
    {	
        $view = new Zend_View;
	    $view->setEncoding('UTF-8');
			
		$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	
        $layout=Zend_Layout::startMvc(
            array(
                'layoutPath' => self::$root . '/application/layouts',
                'layout' => 'login'
                )
        );
	$layout->setViewSuffix("php");
    }

    /**
     * DB setup done here.
     * Read configuration, create db instance & set in registry to use in other part of application.
     */
    public static function setupDatabase()
    {
        $config = self::$registry->configuration;
        $db = Zend_Db::factory($config->db);
        $db->query("SET NAMES 'utf8'");
		$db->query('SET SQL_BIG_SELECTS=1');
        self::$registry->database = $db;
        Zend_Db_Table::setDefaultAdapter($db);
    }

    /**
     * Response will be sent from following function, also set header for response.
     */
    public static function sendResponse(Zend_Controller_Response_Http $response)
    {
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
    	$response->sendResponse();
    }
   
    /**
     * Zend session setting done here.
     */
    public static function setupSession()
    {
        try
	{
            Zend_Session::setOptions(array(
				'save_path' => self::$root . "/tmp/sessions",
				'remember_me_seconds' => 7200,
            ));

            Zend_Session::start();
	}
	catch (Zend_Session_Exception $e)
	{
		$dbLogger->log('Error: ' . $e->getMessage(), 1);
		$fileLogger->log($e->getMessage(), 1);
	}
	$defaultNs = new Zend_Session_Namespace('exim',true);

	Zend_Registry::set("defaultNs", $defaultNs);

	if($config->log_level == 2)
	{
		$fileLogger->info("Sessions setup finished!");
	}
  }
  /**
     * Load a common class function that is used in whole project.
     */
	public function commonClass() {
		include self::$root . "/public/classes/class.salaryslip.pdf.php";
		include self::$root . "/public/classes/class.mailmanager.php";
		include self::$root . "/public/globalvar/Variable.php";
		include self::$root . "/public/classes/class.function.php";
		include self::$root . "/public/classes/class.encryption.php";
		$var = new Variable();
		$var->setDefined();
		Bootstrap::$LabelObj = new ClassSalaryslipPdf('P','mm','a4');
		self::$Mail = new class_mailmanager();
	}
	
  public function showMessage(){
     if(isset($_SESSION[SUCCESS_MSG])){
	     echo $_SESSION[SUCCESS_MSG];
		 unset($_SESSION[SUCCESS_MSG]);
	 }
	 if(isset($_SESSION[ERROR_MSG])){
	   echo $_SESSION[ERROR_MSG];
	   unset($_SESSION[ERROR_MSG]);
	 }
  }
}
?>
