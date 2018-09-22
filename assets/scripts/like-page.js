jQuery(document).ready(function($){

	class Like {
		constructor() {
			this.events();
		}

		events() {
			$('.like-box').on("click", this.ourClickDispatcher.bind(this));
			$('.like-box').hover(
				function() {
					// console.log($(e.target));
					$(this).find('.fa-heart-o').toggleClass('hidden');
					$(this).find('.fa-close').toggleClass('hidden');
					// e.target.classList.toggle('hidden');
				},
				function () {
					$(this).find('.fa-heart-o').toggleClass('hidden');
					$(this).find('.fa-close').toggleClass('hidden');
				}
			);	
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
					console.log(response);
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
					currentLikeBox.parent().remove();
					currentLikeBox.remove();
				},
				error: function(response) {
					console.log(response)
				}
			});
		}
	}
	var like = new Like();
	

});