"use strict"
var urls = {
	sAnswer : "/answer",
	statistic : "/statistic/",
	gotoPage : "/gotoPage",
	startQuiz : "/startQuiz",
	setGuiTheme : "/setGuiTheme"
};

function goTo(questPostition){
	$.post(
		urls.gotoPage,
		{'questPostition':questPostition},
		function(){window.location.href = urls.startQuiz;}
	)
}
function changeGuiTheme(event, theme){
	event.preventDefault(); 
	
	$("#guiTheme").attr('href', '//bootswatch.com/'+theme+'/bootstrap.min.css');
	
	$.post(
		urls.setGuiTheme,
		{'guiTheme':theme},
		successChange
	)
}
$(document).ready(function(){
	
	$('#btnAnswer').bind('click', function(){
		
		var formId = "#answerForm";
		var $elem = $( formId + " input[name=answer]");
		//console.log($( formId ).serialize());
		if (!hasError($elem)){console.log(urls);
			$.post(
				urls.sAnswer,
				$( formId ).serialize(),
				addEditResponse
			)
		}
	});
	
	function hasError(elem){
		if (elem.val() === ''){
			elem.focus();
			elem.closest(".form-group").addClass('has-error');
			return true;
		}
		
		return false;
	}

	function addEditResponse(json){
		var d = $.parseJSON(json);
		
		if (d[0] == 'success'){
			location.reload();
			
		}else{
			alert(d[1]);
		}
	}
	
		
	var QF = {};

	QF.drawFromUrl = function(url, canvasElementId){
		$.ajax({
			url: url,
			dataType: 'json',
			success: function(dataArray){
				var labelRec = dataArray[0];
				var record = dataArray[1];
				
				var datasets = [];
				
				for (var x=0; x<record.length; x++){
					var color = '#'+Math.floor(Math.random()*16777215).toString(16);
					var highlight = '#'+Math.floor(Math.random()*16777215).toString(16);
					
					datasets[x] = {
						//label: "My dataset " + x,
						label: "Price",
						//fillColor: "transparent",
						strokeColor: color,
						pointColor: color,
						pointHighlightStroke: color,
						pointHighlightFill: highlight,
						pointStrokeColor: highlight,
						data: record[x]
					}
				}
				
				var chartData = {
					labels: labelRec,
					datasets: datasets
				};
				var options = {
					responsive: true,
					scaleUse2Y: true,
					bezierCurve: false
				};
				
				var ctx = document.getElementById(canvasElementId).getContext("2d");
				
				var myLineChart = new Chart(ctx).Bar(chartData, options);
			}
		});
	}
	//(QF.updateChart = function(){
		QF.drawFromUrl(urls.statistic + $('input[name=quizLongId]').val(), 'chartarea1');
		//QF.drawFromUrl('http://52.64.190.2/strategy-game/priceRound/53', 'chartarea1');
	//})();
	
});