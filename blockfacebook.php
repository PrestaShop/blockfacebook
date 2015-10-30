<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class BlockFacebook extends Module
{
	public function __construct()
	{
		$this->name = 'blockfacebook';
		$this->tab = 'front_office_features';
		$this->version = '1.5.0';
		$this->author = 'PrestaShop';

		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Facebook Like Box block');
		$this->description = $this->l('Displays a block for subscribing to your Facebook Page.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return parent::install() &&
			Configuration::updateValue('blockfacebook_url', 'https://www.facebook.com/prestashop') &&
			Configuration::updateValue('blockfacebook_hidecover', 0) &&
			Configuration::updateValue('blockfacebook_showfacepile', 1) &&
			Configuration::updateValue('blockfacebook_showposts', 0) &&
			Configuration::updateValue('blockfacebook_smallheader', 0) &&
			Configuration::updateValue('blockfacebook_hidecta', 0) &&
			$this->registerHook('footer') &&
			$this->registerHook('rightColumn') &&
			$this->registerHook('leftColumn') &&
			$this->registerHook('displayHome') &&
			$this->registerHook('displayHeader');
	}

	public function uninstall()
	{
		$this->unregisterHook('displayHeader');
		$this->unregisterHook('displayHome');
		$this->unregisterHook('leftColumn');
		$this->unregisterHook('rightColumn');
	    $this->unregisterHook('footer');

		// Delete configuration
		return Configuration::deleteByName('blockfacebook_url') && 
			Configuration::deleteByName('blockfacebook_hidecover') &&
			Configuration::deleteByName('blockfacebook_showfacepile') &&
			Configuration::deleteByName('blockfacebook_showposts') &&
			Configuration::deleteByName('blockfacebook_smallheader') &&
			Configuration::deleteByName('blockfacebook_hidecta') &&
			parent::uninstall();
	}

	private function assignSmartyVariables()
	{
		$facebookurl = Configuration::get('blockfacebook_url');
		if (!strstr($facebookurl, 'facebook.com'))
			$facebookurl = 'https://www.facebook.com/'.$facebookurl;

		$this->context->smarty->assign(array(
			'facebook_url' => $facebookurl,
			'facebook_hidecover' => Configuration::get('blockfacebook_hidecover')?'true':'false',
			'facebook_showfacepile' => Configuration::get('blockfacebook_showfacepile')?'true':'false',
			'facebook_showposts' => Configuration::get('blockfacebook_showposts')?'true':'false',
			'facebook_smallheader' => Configuration::get('blockfacebook_smallheader')?'true':'false',
			'facebook_hidecta' => Configuration::get('blockfacebook_hidecta')?'true':'false'
		));
	}

	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('blockfacebook_url', Tools::getValue('blockfacebook_url'));
			Configuration::updateValue('blockfacebook_hidecover', Tools::getValue('blockfacebook_hidecover_on'));
			Configuration::updateValue('blockfacebook_showfacepile', Tools::getValue('blockfacebook_showfacepile_on'));
			Configuration::updateValue('blockfacebook_showposts', Tools::getValue('blockfacebook_showposts_on'));
			Configuration::updateValue('blockfacebook_smallheader', Tools::getValue('blockfacebook_smallheader_on'));
			Configuration::updateValue('blockfacebook_hidecta', Tools::getValue('blockfacebook_hidecta_on'));
			$html .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->_clearCache('views/templates/hook/facebook_home.tpl');
			$this->_clearCache('views/templates/hook/facebook_column.tpl');
			Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}

		$html .= $this->renderForm();
		$this->assignSmartyVariables();

		$this->context->smarty->assign(array(
			'facebook_js_url' => $this->_path.'views/js/blockfacebook.js',
			'facebook_css_url' => $this->_path.'views/css/blockfacebook.css'
		));

		$html .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/_configure/preview.tpl');
		return $html;
	}

	public function hookDisplayHome()
	{
		if (!$this->isCached('views/templates/hook/facebook_home.tpl', $this->getCacheId()))
			$this->assignSmartyVariables();

		return $this->display(__FILE__, 'views/templates/hook/facebook_home.tpl', $this->getCacheId());
	}

	public function hookDisplayLeftColumn()
	{
		if (!$this->isCached('views/templates/hook/facebook_column.tpl', $this->getCacheId()))
			$this->assignSmartyVariables();

		return $this->display(__FILE__, 'views/templates/hook/facebook_column.tpl', $this->getCacheId());
	}

	public function hookDisplayRightColumn()
	{
		return $this->hookDisplayLeftColumn();
	}

	public function hookHeader()
	{
		$this->_assignMedia();
	}

	public function hookDisplayFooter()
	{
		return $this->hookDisplayHome();
	}

	protected function _assignMedia()
	{
		$this->context->controller->addCss(($this->_path).'views/css/blockfacebook.css');
		$this->context->controller->addJS(($this->_path).'views/js/blockfacebook.js');
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Facebook link (full URL is required)'),
						'name' => 'blockfacebook_url',
					),
					array(
						'type' => 'checkbox',
						'name' => 'blockfacebook_smallheader',
						'desc' => $this->l('Uses a smaller version of the page header'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'on',
									'name' => $this->l('Use Small Header'),
									'val' => '1'
								),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),
					array(
						'type' => 'checkbox',
						'name' => 'blockfacebook_hidecover',
						'desc' => $this->l('Hide the cover photo in the header'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'on',
									'name' => $this->l('Hide Cover Photo'),
									'val' => '1'
								),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),
					array(
						'type' => 'checkbox',
						'name' => 'blockfacebook_showfacepile',
						'desc' => $this->l('Show profile photos when friends like this'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'on',
									'name' => $this->l('Show Friend\'s Faces'),
									'val' => '1'
								),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),
					array(
						'type' => 'checkbox',
						'name' => 'blockfacebook_showposts',
						'desc' => $this->l('Show posts from the Page\'s timeline'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'on',
									'name' => $this->l('Show Page Posts'),
									'val' => '1'
								),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),
					array(
						'type' => 'checkbox',
						'name' => 'blockfacebook_hidecta',
						'desc' => $this->l('Hide the custom call to action button (if available)'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'on',
									'name' => $this->l('Hide action button'),
									'val' => '1'
								),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'blockfacebook_url' => Tools::getValue('blockfacebook_url', Configuration::get('blockfacebook_url')),
			'blockfacebook_hidecover_on' => Tools::getValue('blockfacebook_hidecover_on', Configuration::get('blockfacebook_hidecover')),
			'blockfacebook_showfacepile_on' => Tools::getValue('blockfacebook_showfacepile_on', Configuration::get('blockfacebook_showfacepile')),
			'blockfacebook_showposts_on' => Tools::getValue('blockfacebook_showposts_on', Configuration::get('blockfacebook_showposts')),
			'blockfacebook_smallheader_on' => Tools::getValue('blockfacebook_smallheader_on', Configuration::get('blockfacebook_smallheader')),
			'blockfacebook_hidecta_on' => Tools::getValue('blockfacebook_hidecta_on', Configuration::get('blockfacebook_hidecta')),
		);
	}
}
