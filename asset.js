// -- Search Tafseer..
function tafseerSearch() {

	if ( $('#SearchAll').is(':checked') ) {
		soraString = '&soraID=0'
	}
	else {
    	soraString = '&soraID=' + $('#sora_id').val();
	}


	if ($('#q').val().length < 3) {
		return false;
	}

	$("#SearchBT").css('disabled',true);
	ToggleStatus();

	$.ajax({
	   type: 'POST',
	   url: 'search.php',
	   data: 'q='+ encodeURIComponent($("#q").val()) + '&' + soraString,
	   dataType: "html",
	   success: function(html) {
			SetOpacity2(0);
			$("#SearchBT").css('disabled', false);
			$('#search').css('visibility','visible');
			SearchFadeIn();
			$('#search').html("<div class=\"popUp\" id=\"searchresult\">" + html + "</div>");
	        $('#search').center({transition:300});
			ToggleStatus();
			CleanExplain2();
	   },
	   error: function(err) {
        	alert(err);
			return false;
	   }
	});
}


function clearSearch() {
	$('#search').html('');
	$('#search').css('visibility','hidden');
}

// -- Opacity For Search...

function SetOpacity2(value) {
	$('#search').css('opacity',value / 10);
}

function SearchFadeIn() {
	for( var i = 0 ; i <= 100 ; i++ )
		setTimeout( 'SetOpacity2(' + (i / 10) + ')' , 8 * i );
}

function SearchFadeOut() {
	for( var i = 0 ; i <= 100 ; i++ ) {
		setTimeout( 'SetOpacity2(' + (10 - i / 10) + ')' , 8 * i );
	}
	setTimeout('clearSearch()', 800 );
}

// -- Opacity..

function setOpacity(value) {
	$('#txt1').css('opacity',value / 10);
//	$('#txt1').style.filter = 'alpha(opacity=' + value * 10 + ')';
}

function fadeIn() {
	for( var i = 0 ; i <= 100 ; i++ )
		setTimeout( 'setOpacity(' + (i / 10) + ')' , 8 * i );
}

function fadeOut() {
	for( var i = 0 ; i <= 100 ; i++ ) {
		setTimeout( 'setOpacity(' + (10 - i / 10) + ')' , 8 * i );
	}
	setTimeout('CleanExplain2()', 800 );
}

// -- Get Sora From DB..


function Sora_Update(id,page) {
 	fadeOut();
	SearchFadeOut();
	ToggleStatus();

	$.ajax({
	   type: 'GET',
	   url: 'index.php',
	   data: '?mode=ajax&id='+ id + '&start=' + page,
	   dataType: "html",
	   success: function(html) {
			$('#content').html(html);
			ToggleStatus();
	   },
	   error: function(err) {
        	alert(err);
			return false;
	   }
	});
}

function QuranViewExplain(id, data, div) {
	setOpacity(0);
	e = $('#'+ id);
	$('#txt1').css('display',"block");
	fadeIn();
	$('#txt1').html("<div class=\"popUp\"><div onClick=\"javaScript:CleanExplain2();\" class=\"PopupClose\">إغـــــــــــــــــلاق</div><HR />" + div + "<HR />" + data + "</div>");
    $('#txt1').center({transition:300});
	clearSearch();
}

function CleanExplain2() {
	$('#txt1').html('');
	$('#txt1').css('display',"none");
}

// -- Center Element.
(function($){
     $.fn.extend({
          center: function (options) {
               var options =  $.extend({ // Default values
                    inside:window, // element, center into window
                    transition: 0, // millisecond, transition time
                    minX:0, // pixel, minimum left element value
                    minY:0, // pixel, minimum top element value
                    withScrolling:false, // booleen, take care of the scrollbar (scrollTop)
                    vertical:true, // booleen, center vertical
                    horizontal:true // booleen, center horizontal
               }, options);
               return this.each(function() {
                    var props = {position:'absolute'};
                    if (options.vertical) {
                         var top = ($(options.inside).height() - $(this).outerHeight()) / 2;
                         if (options.withScrolling) top += $(options.inside).scrollTop() || 0;
                         top = (top > options.minY ? top : options.minY);
                         $.extend(props, {top: top+'px'});
                    }
                    if (options.horizontal) {
                          var left = ($(options.inside).width() - $(this).outerWidth()) / 2;
                          if (options.withScrolling) left += $(options.inside).scrollLeft() || 0;
                          left = (left > options.minX ? left : options.minX);
                          $.extend(props, {left: left+'px'});
                    }
                    if (options.transition > 0) $(this).animate(props, options.transition);
                    else $(this).css(props);
                    return $(this);
               });
          }
     });
})(jQuery);

// -- Ajax Status Indctor

function ToggleStatus() {

 	if ( $('#statusContainer').css('visibility') == 'visible') {
  		$('#statusContainer').css('visibility','hidden');
 	}
 	else {
  		$('#statusContainer').css('visibility','visible');
 	}
}
