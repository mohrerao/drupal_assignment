<?php

namespace Drupal\myfirstmodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Hello block"),
 *   category = @Translation("Hello World"),
 * )
 */
class helloblock extends BlockBase {

	/**
   * {@inheritdoc}
   */
	public function blockForm($form, FormStateInterface $form_state) {
		$form = parent::blockForm($form, $form_state);
		$config = $this->getConfiguration();
		$form['block_title'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('Title'),
			'#description' => $this->t('Who do you want to say hello to?'),
			'#required' => True,
		);
		
		$form['image'] = array (
			'#type' => 'managed_file',
			'#title' => $this->t('Image'),
			'#description' => $this->t('Please upload an image'),
			'#upload_location' => 'public://',
 			'#upload_validators' => [
 				'file_validate_extensions' => ['png', 'jpg', 'jpeg']
  			],
			'#required' => True,
		);

		$form['description'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('Description'),
			'#description' => $this->t('Please add description'),
			'#required' => True,
		);

		$form['ipaddress'] = array (
			'#type' => 'textfield',
			'#title' => $this->t('IP Address to be blocked'),
			'#description' => $this->t('Please add ip addresses separated by commas'),
		);
		
		return $form;
	}

	/**
	* {@inheritdoc}
	*/
	public function blockSubmit($form, FormStateInterface $form_state) {

		$this->setConfigurationValue('title', $form_state->getValue('block_title'));
		//Save image permanently
		$image = $form_state->getValue('image');
		$file = \Drupal\file\Entity\File::load($image[0]); 	
	   	$file->setPermanent();
	  	$file->save();
	  	// $this->setConfigurationValue('image', $image[0]);
		$this->setConfigurationValue('description', $form_state->getValue('description'));
		$this->setConfigurationValue('ipaddress', $form_state->getValue('ipaddress'));
	}

	/**
	* {@inheritdoc}
 	*/
	protected function blockAccess(AccountInterface $account) {
		$config = $this->getConfiguration();
		$ipaddresses = explode(",", $config['ipaddress']);
		$clientip = $this->getUserIpAddr();
		
		//If no ip address is added for blocked list
		if (isset($ipaddresses) && is_null($ipaddresses)){
			return AccessResult::allowed();
		}
		else{
			//block access if client ip matches one from the ip list
			foreach ($ipaddresses as  $value) { 
				if (trim($clientip) == trim($value)){
					return AccessResult::forbidden();
				}
			}
			return AccessResult::allowed();
		}
	}

	/**
	* Get IP address of client 
	*/
	public function getUserIpAddr(){
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	        //ip from share internet
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	        //ip pass from proxy
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}

	/**
	* {@inheritdoc}
	*/
	public function build() {
		$config = $this->getConfiguration();
		//create image variable for twig template
		$image = $config['image'];
		
		if (!empty($image[0])) {
			if ($file = \Drupal\file\Entity\File::load($image)) {
				$imagearr = array('#theme' => 'image_style', '#style_name' => 'medium', '#uri'=> $file->getFileUri());
			}
		}
		
		
		return array(
			'#theme' => 'helloblock',
			'#blocktitle' => $this->t($config['title']),
			'#blockdescription' => $this->t($config['description']),
			'#blockimage' => $imagearr,
			'#ip' => $config['ipaddress'],
		);
    }
}
