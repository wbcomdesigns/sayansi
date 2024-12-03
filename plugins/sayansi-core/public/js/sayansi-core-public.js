(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function() {
		$('#business-beam-line-tag').chosen();

		$(document).on( 'keyup', '#business-excerpt, #acf-field_6729c0127e757', function() {
			var maxlength = $(this).attr("maxlength");
			var currentLength = $(this).val().length;

			 if (currentLength >= maxlength) {
			    alert("You have reached the maximum number of characters.");
			 }
		} )

		//Add code for assign random color for hashtag

		// Function to generate a random color
		function getRandomColor() {
			const letters = '0123456789ABCDEF';
			let color = '#';
			for (let i = 0; i < 6; i++) {
				color += letters[Math.floor(Math.random() * 16)];
			}
			return color;
		}

		// Select all anchor tags with the class 'hashtag'
		const hashtagAnchors = document.querySelectorAll('a.hashtag');

		// Apply random color to each anchor
		hashtagAnchors.forEach(hashtagAnchors => {
			hashtagAnchors.style.color = getRandomColor();
		});

		//code end for assign random color for hashtag

		//Change group document tab name to My libraray
		jQuery(document).ready(function ($) {
			
			//Group Description tab
			if ($('#description-groups-li .bb-single-nav-item-point').length > 0) {
				$('#description-groups-li .bb-single-nav-item-point').hide();
			}
			//Group Activity tab
			if ($('#activity-groups-li .bb-single-nav-item-point').length > 0) {
				$('#activity-groups-li .bb-single-nav-item-point').text('Group Activity');
			}
			//Group documents tab
			if ($('#documents-groups-li .bb-single-nav-item-point').length > 0) {
				$('#documents-groups-li .bb-single-nav-item-point').text('Group Library');
			}
			//Group member tab
			if ($('#members-groups-li .bb-single-nav-item-point').length > 0) {
				$('#members-groups-li .bb-single-nav-item-point').text('Group Members');
			}
			//Group Discussion tab
			if ($('#nav-forum-groups-li .bb-single-nav-item-point').length > 0) {
				$('#nav-forum-groups-li .bb-single-nav-item-point').text('Group Discussion');
			}
			// Hide Group Photos tab
			if ($('#photos-groups-li').length > 0) {
				$('#photos-groups-li').hide();
			}
			// Hide Group Albums tab
			if ($('#albums-groups-li').length > 0) {
				$('#albums-groups-li').hide();
			}
			// Hide Group Videos tab
			if ($('#videos-groups-li').length > 0) {
				$('#videos-groups-li').hide();
			}
		});
		//End change group document tab name to My libraray
		
	});
})( jQuery );
