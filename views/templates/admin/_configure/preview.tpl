<script src="{$facebook_js_url}"></script>
<link href="{$facebook_css_url}" rel="stylesheet">
{if $facebook_url != ''}
<div class="bootstrap panel">
	<div class="panel-heading">
		<i class="icon-picture-o"></i> {l s='Preview' mod='blockfacebook'}
	</div>
	<div id="fb-root"></div>
	<div id="facebook_block">
		<h4 >{l s='Follow us on Facebook' mod='blockfacebook'}</h4>
		<div class="facebook-fanbox">
			<div class="fb-page" data-href="{$facebook_url|escape:'html':'UTF-8'}" data-adapt_container_width="true" data-hide-cover="{$facebook_hidecover}" data-show-facepile="{$facebook_showfacepile}" data-show-posts="{$facebook_showposts}" data-small-header="{$facebook_smallheader}" data-hide-cta="{$facebook_hidecta}">
			</div>
		</div>
	</div>
</div>
{/if}
