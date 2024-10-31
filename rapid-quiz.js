jQuery(document).ready(function($){
				
					
			$('.rq_radio').click(function() {
						
				
			if($(this).parent().parent().siblings('.rq_question').attr("data-answered") == "no") {
				
				var thisAnswer =  $(this).attr("id");
				var userAnswer = '';
								
							
						if($(this).parent().attr("data-correct")) {
							
							$(this).parent().css('font-weight', 'bold');
							
							$(this).parent().siblings('.rq_notes').addClass("rq_correct");
							$(this).parent().parent().siblings('.rq_question').children('.correct').css('display', 'inline-block');
							
							userAnswer = "correct";
							
						} else {
							
							$(this).parent().css('text-decoration', 'line-through');
							$(this).parent().parent().siblings('.rq_question').children('.incorrect').css('display', 'inline-block');
							
							userAnswer = "wrong";
							
							
						}
						
									
				
						// to do - remove hovers after question has been answered
						//$(this).parent().parent().siblings('.rq_option_text').children().unbind('mouseenter mouseleave');
				
						// don't show note unless there's text there
						if ($(this).parent().siblings('.rq_notes').text()){
						
						// add correct class depending on answer
						$(this).parent().siblings('.rq_notes').addClass("rq_"+userAnswer);
						
						// fade 'er up cap'n
						$(this).parent().siblings('.rq_notes').fadeTo(500, 1);
						
						}
				
				// block further answers
				$(this).parent().parent().siblings('.rq_question').attr("data-answered", "yes");
				//$(this).parent().parent().siblings('.rq_question').siblings('.rq_option_text').unbind('mouseenter mouseleave');
				
				$(this).prop('disabled', true);
				
				} else {
				
				$(this).attr('checked', false);
				return false;
				// stops radio button from working if question has already been answered
			
				  // $(this).parent().unbind('mouseenter mouseleave');				;
									
						
				}
			
			});
			
			
			
		});
	