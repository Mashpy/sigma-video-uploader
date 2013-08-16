/**
 * post-moderation.js
 *
 * Improve the moderation panel, required because of WordPress limitations.
 *
 * @version		20120730
 * @package		Sigma Video
 * @subpackage	Admin Post Moderation
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */
(function($, options)
	{
		$(document).ready(function(e) {
			// Reposition the 'Remove All Spam' button
			$('#fvcn-remove-all-spam-submit').appendTo( $('#fvcn-remove-all-spam-submit').parent() );
			
			// Add bulk actions
			var status = $('#_fvcn_post_status').val();
			
			if (options.ps.all == status) {
				var actions = '<option value="fvcn-bulk-publish">' + options.locale.publish + '</option>'
							+ '<option value="fvcn-bulk-unpublish">' + options.locale.unpublish + '</option>'
							+ '<option value="fvcn-bulk-spam">' + options.locale.spam + '</option>';
			}
			else if (options.ps.public == status) {
				var actions = '<option value="fvcn-bulk-unpublish">' + options.locale.unpublish + '</option>'
							+ '<option value="fvcn-bulk-spam">' + options.locale.spam + '</option>';
			}
			else if (options.ps.pending == status) {
				var actions = '<option value="fvcn-bulk-publish">' + options.locale.publish + '</option>'
							+ '<option value="fvcn-bulk-spam">' + options.locale.spam + '</option>';
			}
			else if (options.ps.trash == status) {
				var actions = '<option value="fvcn-bulk-spam">' + options.locale.spam + '</option>';
			}
			else if (options.ps.spam == status) {
				var actions = '<option value="fvcn-bulk-publish">' + options.locale.publish + '</option>';
			}
			
			$('select[name="action"]').append( actions );
			$('select[name="action2"]').append( actions );
		});
	}
)(jQuery, FvCommunityNewsAdminPostModeration);
