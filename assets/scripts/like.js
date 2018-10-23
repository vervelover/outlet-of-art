jQuery(document).ready(function($){

	class Like {
		constructor() {
			this.events();
		}

		events() {
			$('.like-box').on("click", this.ourClickDispatcher.bind(this));			
		}

		ourClickDispatcher(e) {
			var currentLikeBox = $(e.target).closest(".like-box");

			if (currentLikeBox.attr('data-exists') == 'yes') {
				this.deleteLike(currentLikeBox);
			} else {
				this.createLike(currentLikeBox);
			}
		}

		createLike(currentLikeBox) {
			$.ajax({
				beforeSend: function(xhr) {
	                xhr.setRequestHeader('X-WP-Nonce', likeartworksData.nonce);
	            },
				url: likeartworksData.root_url + '/wp-json/likeartworks/v1/manageLike',
				type: 'POST',
				data: {'artworkId': currentLikeBox.data('artwork')},
				success: function(response) {
					currentLikeBox.attr('data-exists', 'yes');
					var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
					likeCount++;
					currentLikeBox.find('.like-count').html(likeCount);
					currentLikeBox.attr('data-like', response);
					currentLikeBox.find('.like-tooltiptext').html(likeartworksData.itemRemoveText);
					// console.log(response);
				},
				error: function(response) {
					console.log(response)
				}
			});
		}

		deleteLike(currentLikeBox) {
			$.ajax({
				beforeSend: function(xhr) {
	                xhr.setRequestHeader('X-WP-Nonce', likeartworksData.nonce);
	            },
				url: likeartworksData.root_url + '/wp-json/likeartworks/v1/manageLike',
				data: {'like': currentLikeBox.attr('data-like')},
				type: 'DELETE',
				success: function(response) {
					currentLikeBox.attr('data-exists', 'no');
					var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
					likeCount--;
					currentLikeBox.find('.like-count').html(likeCount);
					currentLikeBox.attr('data-like', '');
					currentLikeBox.find('.like-tooltiptext').html(likeartworksData.itemAddText);
					// console.log(response);		
				},
				error: function(response) {
					console.log(response)
				}
			});
		}
	}
	var like = new Like();
	

});