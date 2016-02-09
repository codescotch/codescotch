$(document).ready(function() {

	$('.quality-score-link').click(function() {

		var score = $(this).text();
		var scoreColor = $(this).attr('name');
		var span = $(this).parent().find('.quality-score');		
		span.text(score);
		span.removeClass();
		span.addClass('quality-score');
		span.addClass(scoreColor);
	});

	$('.relevance-score-link').click(function() {

		var score = $(this).text();
		var scoreColor = $(this).attr('name');
		var span = $(this).parent().find('.relevance-score');		
		span.text(score);
		span.removeClass();
		span.addClass('relevance-score');
		span.addClass(scoreColor);
	});

});