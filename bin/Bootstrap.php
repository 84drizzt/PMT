<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
    {
        
        Zend_Loader_Autoloader::getInstance()->registerNamespace("My")->pushAutoloader(
        new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => dirname(__FILE__),
        )));
        
    }

	public function _initConfig() {
		Zend_Registry::set('config', $this->getOption('configs'));
	}
	
	public function _initLogger(){
		$logger = new Zend_Log ();
		$config = $this->getOption('configs');
		$logLevel = (int) $config["logLevel"];

		$logWriter =  new $config["logWriter"]($config["logFile"]);
		$logger->addWriter ($logWriter );
		$logger->addFilter(new Zend_Log_Filter_Priority($logLevel));
		Zend_Registry::set("logger", $logger);
	}
	

}

