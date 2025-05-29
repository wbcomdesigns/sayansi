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
				$('#nav-forum-groups-li .bb-single-nav-item-point').text('Group Discussions');
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

		// add code load template on all forum menu corresponding to forum and discussion tab
		jQuery(document).ready(function ($) {
			jQuery('.forum-subnav li a').on('click', function (e) {
				e.preventDefault(); // Prevent the default anchor click behavior
				// Remove 'selected' class from all tabs
				$('.forum-subnav li a').removeClass('selected');

				// Add 'selected' class to the clicked tab
				$(this).addClass('selected');
				var check_tab = $(this).data('id');
				console.log(check_tab);
				if( 'sayansi-discussion' == check_tab ){
					const body = document.body;					
					body.classList.remove("sayansi-forum");
					body.classList.add("sayansi-discussion");
					$('.sayansi-discussion .bp-dir-search-form').hide();
					$('.sayansi-discussion #forums-filters').hide();
					$('.sayansi-discussion .grid-filters').hide();

				}
				if( 'sayansi-forum' == check_tab ){
					const body = document.body;
					body.classList.remove("sayansi-discussion");
					body.classList.add("sayansi-forum");
					$('.sayansi-forum .bp-dir-search-form').show();
					$('.sayansi-forum #forums-filters').show();
					$('.sayansi-forum .grid-filters').show();
				}

				if (check_tab == 'sayansi-create-forum') {
					var createForumUrl = $(this).attr('href'); // Get the URL from the href attribute
					window.location.href = createForumUrl; // Redirect to the "Create a Forum" page
					return; // Exit the function
				}
				$.ajax({
					url: sayansi_ajax_object.ajax_url,
					type: "post",
					data: {
						'action': 'load_forum_discussion',
						'check_tab': check_tab,
						'forum_desc': sayansi_ajax_object.forum_desc,
						'nonce': sayansi_ajax_object.ajax_nonce
					},
					success: function (data) {
						// Check if the response is successful
						if (data.success) {
							// Replace the content in the response container
							$('#response-container').html(data.data); // Replace the content
						} else {
							console.error('Error:', data.data); // Log any error message returned from the server
						}
					},
					error: function (errorThrown) {
						console.log('AJAX error:', errorThrown);
					}
				});
			});
		});
		// End add code load template on all forum menu corresponding to forum and discussion tab
		
		//swap the course and forums tab
		jQuery(document).ready(function ($) {
			// Get the list items representing the tabs
			var forumsTab = document.getElementById('forums-personal-li');
			var coursesTab = document.getElementById('courses-personal-li');
			if (forumsTab && coursesTab) {
				// Get the parent <ul> element
				var parentList = forumsTab.parentElement;

				// Swap the positions of the tabs
				parentList.insertBefore(forumsTab, coursesTab );				
			}
		});
		//end swap the course and forums tab


		jQuery(document).ready(function($) {
			var business_id = sayansi_ajax_object.business_id;
			if( sayansi_ajax_object.check_group_component ){
				return;
			}		
			// Forums search functionality
			function fetchForums(query = '', order = 'alphabetical', forum_order = '', selectedLayout = 'grid', paged = 1 ) {
				$.ajax({
					url: sayansi_ajax_object.ajax_url,
					type: 'POST',
					data: {
						action: 'forums_search',
						query: query,
						order: order,
						forum_order: forum_order,
						layout: selectedLayout,
						nonce: sayansi_ajax_object.ajax_nonce,
						paged: paged
					},
					success: function(response) {
						$('#response-container').html(response);
					}
				});
			}
		
			// Forum Search functionality
			$('#bbpress-forums-search').on('keyup', function() {
				var searchQuery = $(this).val();
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view				
				fetchForums(searchQuery, 'alphabetical', '', selectedLayout );
			});

			// Prevent form submission on Enter key press
			$('#bbpress-forums-search').on('keydown', function(e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					var searchQuery = $(this).val();
					fetchForums(searchQuery);
				}
			});
		
			// Reset functionality on by default
			$('.search-form_reset').on('click', function(e) {
				e.preventDefault();
				$('#bbpress-forums-search').val('');
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view  
				fetchForums( '', 'alphabetical', '', selectedLayout );
			});

			// Filter the forum alphabatically or recently 
			$('#forums-order-by').on('change', function() {
				var forum_order = $(this).val();
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view
				fetchForums( '', 'alphabetical', forum_order, selectedLayout );				
			});		

			//Add active, grid, list class also maintain the classes on page nevigation
			var savedLayout = localStorage.getItem('forumLayout') || 'grid'; // Default to grid if not set
			// Set the default active view based on saved layout
		    $('.layout-view').removeClass('active'); // Remove active class from all
		    $('.layout-view[data-view="' + savedLayout + '"]').addClass('active'); // Add active class to saved layout
		    $('#bbpress-forums ul').addClass(savedLayout); // Set the default class for the forum list

		    //for user course tab display selected layout( course-grid/curse-list ) after page reload
		    // Check if the body has the class 'post-type-archive-mpcs-course'
			if ( $('body').hasClass('post-type-archive-mpcs-course') || $('body').hasClass('user-courses') ) {
			    var savedcourseLayout = localStorage.getItem('forumLayout') || 'course-grid';
			    $('.layout-view').removeClass('active'); // Remove active class from all
			    $('.layout-view[data-view="' + savedcourseLayout + '"]').addClass('active'); // Add active class to saved layout
			    $('.mpcs-cards').addClass(savedcourseLayout); // Set the default class for the forum list
			}

			$('.layout-view').on('click', function(e) {
				e.preventDefault(); // Prevent default anchor behavior
				var view = $(this).data('view'); // Get the view type (grid or list)
				$('.layout-view').removeClass('active');
				$(this).addClass('active');

				localStorage.setItem('forumLayout', view);
				// Remove existing classes and add the new one
				var $forumList = $('#bbpress-forums ul');
				$forumList.removeClass('grid list'); // Remove both classes
				$forumList.addClass(view); // Add the selected view class

				// for course and course directory 
				var $courseList = $('.mpcs-cards');
				$courseList.removeClass('course-grid course-list');
				$courseList.addClass(view);

				// for course directory
				// var $coursedirectory = $('.columns .mpcs-cards');
				// $coursedirectory.removeClass('course-grid course-list');
				// $coursedirectory.addClass(view);

			});
			//end add active, grid, list class also maintain the classes on page nevigation

			//Manage pagination click
			$(document).on('click', '.bbp-pagination a', function(e) {
		        e.preventDefault();
		        var page = $(this).text(); // Get the page number from the link		        
		        var searchQuery = $('#bbpress-forums-search').val();
		        var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view
		        fetchForums(searchQuery, 'alphabetical', '', selectedLayout, page); // Pass the page number
		    });

		    // this code for course filter
			$('#courses-order-by').change(function() {
				var tabname = $('#courses-filters').data('tab');				
				var selectedValue = $(this).val();				
				$.ajax({
					url: sayansi_ajax_object.ajax_url,
					type: 'POST',
					data: {
						action: 'filter_courses',
						order_by: selectedValue,
						nonce: sayansi_ajax_object.ajax_nonce,
						tabname: tabname
					},
					success: function(response) {
						// Replace the courses listing with the new content
						$('.columns.mpcs-cards').html(response);
					}
				});
			});


			$( "#business-group" ).selectize({
				plugins: ["remove_button"],
				delimiter: ",",
				persist: false,
				create: false, // Do not allow new options to be created
				sortField: "text", // Sort options alphabetically
				create: function (input) {
				  return {
					value: input,
					text: input,
				  };
				},
			  });

			$("#partner_groups").selectize({
				plugins: ["remove_button"],
				delimiter: ",",
				persist: false,
				create: false, // Do not allow new options to be created
				sortField: "text", // Sort options alphabetically
				create: function (input) {
				  return {
					value: input,
					text: input,
				  };
				},
				onItemAdd: function(values) {
					// Use AJAX to send the data to a server-side script (PHP, for example)
					$.ajax({
						url: sayansi_ajax_object.ajax_url,
						type: 'POST', 
						data: {
							action: 'update_partner_groups',
							business_id: business_id,
							nonce: sayansi_ajax_object.ajax_nonce,
							selected_groups: values
						},
						success: function(response) {							
							alert("Group added successfully");
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error:', error); // Handle any errors in the request
						}
					});
				},
				onItemRemove: function (value) {					
					$.ajax({
						url: sayansi_ajax_object.ajax_url,
						type: "POST",
						data: { 
							action: "remove_business_group", 
							business_id: business_id,
							nonce: sayansi_ajax_object.ajax_nonce,
							group_id: value // Send removed group ID
						},
						success: function (response) {
							alert("Group Removed successfully");
						},
						error: function (xhr, status, error) {
							console.log("Remove Error:", error);
						}
					});
				}
			});

			// add search for course in user profile
			function fetchCourses(searchQuery = '') {
				$.ajax({
					url: sayansi_ajax_object.ajax_url,
					type: 'POST',
					data: {
						action: 'search_courses_user_profile',
						nonce: sayansi_ajax_object.ajax_nonce,
						s: searchQuery
					},
					success: function(response) {						
						$('.mpcs-cards').html(response);
					}
				});
			}

			$('#course-search-input').on('keyup', function() {				
				var searchQuery = $(this).val().trim();
				fetchCourses(searchQuery);
			});
			// end add search for course in user profile


			// search, filter on user profile network->all inidividual tab
			// Forums search functionality
			function fetchMembers(searchUser = '', order = 'alphabetical', forum_order = '', selectedLayout = 'grid', paged = 1 ) {
				$.ajax({
					url: sayansi_ajax_object.ajax_url,
					type: 'POST',
					data: {
						action: 'indiviual_members_search',
						user: searchUser,
						order: order,
						forum_order: forum_order,
						layout: selectedLayout,
						nonce: sayansi_ajax_object.ajax_nonce,
						paged: paged
					},
					success: function(response) {
						$('#members-dir-list').html(response);
					}
				});
			}
		
			// Forum Search functionality
			$('#individual-member-search').on('keyup', function() {
				var searchUser = $(this).val();
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view							
				fetchMembers(searchUser, 'alphabetical', '', selectedLayout );
			});

			// Prevent form submission on Enter key press
			$('#individual-member-search').on('keydown', function(e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					var searchQuery = $(this).val();
					fetchMembers(searchQuery);
				}
			});
			
			// Filter the member alphabatically or recently 
			$('#individual-members-order-by').on('change', function() {
				var members_order = $(this).val();
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view
				fetchMembers( '', 'alphabetical', members_order, selectedLayout );				
			});	

			// end search, filter on user profile network->all inidividual tab

			//Add active, grid, list class also maintain the classes on page nevigation
			var savedLayout = localStorage.getItem('forumLayout') || 'grid'; // Default to grid if not set			
		   	$('#members-list').removeClass('grid list').addClass(savedLayout);

			$('.layout-view').on('click', function(e) {
				e.preventDefault(); // Prevent default anchor behavior

				var view = $(this).data('view'); // Get the view type (grid or list)
				
				$('.layout-view').removeClass('active');
				$(this).addClass('active');

				localStorage.setItem('forumLayout', view);
				// Remove existing classes and add the new one
				$('#members-list').removeClass('grid list').addClass(view);
				$('#business-list').removeClass('grid list').addClass(view);			

			});

			// search, filter , layout on user profile network->all partner tab
			function fetchPartners(searchQuery = '', order = 'alphabetical', partner_order = '', selectedLayout = 'grid', paged = 1 ) {
				$.ajax({
					url: sayansi_ajax_object.ajax_url,
					type: 'POST',
					data: {
						action: 'network_all_partners',
						nonce: sayansi_ajax_object.ajax_nonce,
						query: searchQuery,
						order: order,
						partner_order: partner_order,
						layout: selectedLayout,
						paged: paged
					},
					success: function(response) {
						$('#business-list-container').html(response);
					}
				});
			}
		
			// Forum Search functionality
			$('#network-all-partners-search').on('keyup', function() {
				var searchQuery = $(this).val();				
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view							
				fetchPartners(searchQuery, 'alphabetical', '', selectedLayout );
			});

			// Filter the member alphabatically or recently 
			$('#network-all-partners-order-by').on('change', function() {
				var partner_order = $(this).val();
				var selectedLayout = $('.layout-view.active').data('view'); // Get the active layout view
				fetchPartners( '', 'alphabetical', partner_order, selectedLayout );				
			});	

			//Add active, grid, list class also maintain the classes on page nevigation
			var savedLayout = localStorage.getItem('forumLayout') || 'grid'; // Default to grid if not se			
			// $('#business-list').addClass(savedLayout); // Set the default class for the forum list

			var $businessList = $('#business-list-container ul');
			$businessList.removeClass('grid list'); // Remove both classes
			$businessList.addClass(savedLayout);

			// end search, filter , layout on user profile network->all partner tab

			// this is for that hide save partner setting button from the partner/settings/about
			$('.bp-profile-setting-sub-tab').click( function(){
				$('body').removeClass('about_list');
				// Check if this is the About tab
				if ($(this).data('id') === 'about') {
					$('body').addClass('about_list');
				}				
			});


			// color option display according to resume layout on edit resume page
			$(document).on("click", ".bprm-resume-layouts", function () {
				var layout_value = this.value;
				if (layout_value == "one") {
					$(".bprm-layout-one").show();
					$(".bprm-layout-two").hide();
					$(".bprm-layout-three").hide();
				} else if (layout_value == "two") {
					$(".bprm-layout-one").hide();
					$(".bprm-layout-two").show();
					$(".bprm-layout-three").hide();
				} else if (layout_value == "three") {
					$(".bprm-layout-one").hide();
					$(".bprm-layout-two").hide();
					$(".bprm-layout-three").show();
				}
			});
			
		});
		
	});
})( jQuery );
