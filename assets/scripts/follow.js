jQuery(document).ready(function($){

	class Follow {
		constructor() {
			this.events();
		}

		events() {
			$('.follow-box').on("click", this.ourClickDispatcher.bind(this));			
		}

		ourClickDispatcher(e) {
			var currentFollowBox = $(e.target).closest(".follow-box");

			if (currentFollowBox.attr('data-exists') == 'yes') {
				this.deleteFollow(currentFollowBox);
			} else {
				this.createFollow(currentFollowBox);
			}
		}

		createFollow(currentFollowBox) {
			$.ajax({
				beforeSend: function(xhr) {
	                xhr.setRequestHeader('X-WP-Nonce', followedArtistsData.nonce);
	            },
				url: followedArtistsData.root_url + '/wp-json/followartist/v1/manageFollow',
				type: 'POST',
				data: {'artistId': currentFollowBox.data('artist')},
				success: function(response) {
					currentFollowBox.attr('data-exists', 'yes');
					var followCount = parseInt(currentFollowBox.find(".follow-count").html(), 10);
					followCount++;
					currentFollowBox.find('.follow-count').html(followCount);
					currentFollowBox.attr('data-follow', response);
					console.log(response);
				},
				error: function(response) {
					console.log(response)
				}
			});
		}

		deleteFollow(currentFollowBox) {
			$.ajax({
				beforeSend: function(xhr) {
	                xhr.setRequestHeader('X-WP-Nonce', followedArtistsData.nonce);
	            },
				url: followedArtistsData.root_url + '/wp-json/followartist/v1/manageFollow',
				data: {'follow': currentFollowBox.attr('data-follow')},
				type: 'DELETE',
				success: function(response) {
					currentFollowBox.attr('data-exists', 'no');
					var followCount = parseInt(currentFollowBox.find(".follow-count").html(), 10);
					followCount--;
					currentFollowBox.find('.follow-count').html(followCount);
					currentFollowBox.attr('data-follow', '');
					console.log(response);		
				},
				error: function(response) {
					console.log(response)
				}
			});
		}
	}
	var follow = new Follow();
	

});