<?php
/**
 * HOAuth provides widget with buttons for login with social networs 
 * that enabled in HybridAuth config
 * 
 * @uses CWidget
 * @version 1.2.2
 * @copyright Copyright &copy; 2013 Sviatoslav Danylenko
 * @author Sviatoslav Danylenko <dev@udf.su> 
 * @license PGPLv3 ({@link http://www.gnu.org/licenses/gpl-3.0.html})
 * @link https://github.com/SleepWalker/hoauth
 */

/**
 * NOTE: If you want to change the order of button it is better to change this order in HybridAuth config.php file
 */
class HOAuth extends CWidget
{
  /**
   * @var string $route id of module and controller (eg. module/controller) for wich to generate oauth urls
   */
  public $route = false;
  
  //to use for just one provider: $this->widget('ext.hoauth.widgets.HOAuth', array('provider'=>'facebook'));
  public $provider = false; 

  public function init()
  {
    if(!$this->route)
      $this->route = $this->controller->module ? $this->controller->module->id . '/' . $this->controller->id : $this->controller->id;
    
    require_once(dirname(__FILE__).'/../models/UserOAuth.php');
    require_once(dirname(__FILE__).'/../HOAuthAction.php');
    $this->registerFiles();
  }

  public function run()
  {
    $config = UserOAuth::getConfig();
   
    if($this->provider) {
    
    	$provider = $config['providers'][$this->provider];
    	$this->render('link', array(
          'provider' => $this->provider,
        ));
      
    } else {
      
	    foreach($config['providers'] as $provider => $settings)
	      if($settings['enabled'])
	        $this->render('link', array(
	          'provider' => $provider,
	        ));
        
    }
  }

  protected function registerFiles()
  {
    $assetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.hoauth.widgets.assets'));
    Yii::app()->getClientScript()->registerCssFile($assetsUrl.'/css/zocial.css');
  }
}
