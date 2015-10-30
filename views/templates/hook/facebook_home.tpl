{*
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
*}
{if $facebook_url != ''}
<div id="fb-root"></div>
<div id="facebook_block" class="col-xs-4">
	<h4>{l s='Follow us on Facebook' mod='blockfacebook'}</h4>
	<div class="facebook-fanbox">
		<div class="fb-page" data-href="{$facebook_url|escape:'html':'UTF-8'}" data-adapt_container_width="true" data-hide-cover="{$facebook_hidecover}" data-show-facepile="{$facebook_showfacepile}" data-show-posts="{$facebook_showposts}" data-small-header="{$facebook_smallheader}" data-hide-cta="{$facebook_hidecta}">
			<div class="fb-xfbml-parse-ignore">
				<blockquote cite="{$facebook_url|escape:'html':'UTF-8'}"><a href="{$facebook_url|escape:'html':'UTF-8'}">{$shop_name}</a></blockquote></div>
			</div>
		</div>
	</div>
</div>
{/if}
