<?php
class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
	public function actions()
	{
		return array(
			'oauth' => array(
				// the list of additional properties of this action is below
				'class'=>'ext.hoauth.HOAuthAction',
				// Yii alias for your user's model, or simply class name, when it already on yii's import path
				// default value of this property is: User
				'model' => 'CmsUser', 
				// map model attributes to attributes of user's social profile
				// model attribute => profile attribute
				// the list of avaible attributes is below
				'attributes' => array(
					'email' => 'email',
					'firstname' => 'firstName',
					'lastname' => 'lastName',
					// you can also specify additional values, 
					// that will be applied to your model (eg. account activation status)
					'status' => 2,
				),
			),
			// this is an admin action that will help you to configure HybridAuth 
			// (you must delete this action, when you'll be ready with configuration, or 
			// specify rules for admin role. User shouldn't have access to this action!)
			/*'oauthadmin' => array(
				'class'=>'ext.hoauth.HOAuthAdminAction',
			),*/
		);
	}
    
    public function actionFacebook()
    {
	    $this->widget('ext.hoauth.widgets.HOAuth');
    }
}