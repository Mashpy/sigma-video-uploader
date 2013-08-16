/**
 * fvcn-ajax.js
 *
 * Ajax functions
 *
 * @package		Sigma Video
 * @subpackage	Javascript
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

(function($, options) {
	
	$(document).ready(function() {
		if ($('.fvcn-post-form').length) {
			var fvcnFormAjax = new FvCommunityNewsFormAjax();
		}
	});
	
	var FvCommunityNewsFormAjax = function()
	{
		this.createLoader();
		var o = this;
		
		$.ajaxSetup ({
			cache: false
		});
		
		$('.fvcn-post-form-new-post').ajaxForm({
			url: options.ajaxurl,
			data: {
				nonce:  options.nonce,
				action: options.action
			},
			dataType: 'json',
			beforeSend: function() {
				o.showLoader();
				o.disableSubmitButton();
				$('.fvcn-post-form-ajax-progress-bar-inner').width('0%');
			},
			uploadProgress: function(event, position, total, percentComplete) {
				if ('0' == options.thumbnail) {
					return;
				}
				
				$('.fvcn-post-form-ajax-progress-bar-inner').width( percentComplete + '%' );
			},
			success: function(response) {
				o.hideLoader();
				o.enableSubmitButton();
				o.clearAllMessages();
				
				if ('true' == response.success) {
					if ('' == response.permalink || '' != response.message) {
						o.hideForm();
						o.showResponseMessage(response.message);
					} else {
						window.location.href = response.permalink;
					}
				} else {
					$.each(response.errors, function(field, error) {
						o.displayMessage(field, error);
					});
				}
			}
		});
	};
	
	FvCommunityNewsFormAjax.prototype.createLoader = function()
	{
		if ('1' == options.thumbnail) {
			$('.fvcn-post-form-new-post').append('<div class="fvcn-post-form-ajax-loader"><p>' + options.locale.loading + '&hellip;</p><div class="fvcn-post-form-ajax-progress-bar-outer"><div class="fvcn-post-form-ajax-progress-bar-inner"></div></div></div>');
		} else {
			$('.fvcn-post-form-new-post').append('<div class="fvcn-post-form-ajax-loader"><p>' + options.locale.loading + '&hellip;</p></div>');
		}
	};
	
	FvCommunityNewsFormAjax.prototype.showLoader = function()
	{
		$('.fvcn-post-form-ajax-loader').show();
	};
	
	FvCommunityNewsFormAjax.prototype.hideLoader = function()
	{
		$('.fvcn-post-form-ajax-loader').hide();
	};
	
	FvCommunityNewsFormAjax.prototype.disableSubmitButton = function()
	{
		$('#fvcn_post_form_submit').attr('disabled', 'disabled');
	};
	
	FvCommunityNewsFormAjax.prototype.enableSubmitButton = function()
	{
		$('#fvcn_post_form_submit').removeAttr('disabled');
	};
	
	FvCommunityNewsFormAjax.prototype.hideForm = function()
	{
		$('.fvcn-post-form').slideUp('fast');
	};
	
	FvCommunityNewsFormAjax.prototype.showResponseMessage = function(message)
	{
		$('.fvcn-post-form').parent().append('<div class="fvcn-post-added">' + message + '</div>');
	};
	
	FvCommunityNewsFormAjax.prototype.clearAllMessages = function()
	{
		$('.fvcn-error').html('');
	};
	
	FvCommunityNewsFormAjax.prototype.displayMessage = function(field, message)
	{
		$('.' + field.replace(/_/g, '-') + ' > .fvcn-error').html('<ul class="fvcn-template-notice error"><li>' + message + '</li></ul>');
	};
	
})(jQuery, FvCommunityNewsJavascript);
