var week = new Date().getDay();
if (week == 0) {  
   	week = 7;  
	}
var num = week + 1;
$("#nav li:nth-child("+num+")").attr("class","am-active");

$.ajax({
	url: "././data/week.json",
	type: "GET",
	cache: false,
	dataType: "json", 
	success: function(data) {
    	var html = template('tmpl', data);
		document.getElementById('content').innerHTML = html;
		$("#tab"+week).attr("class","am-tab-panel am-active");
	}
})

var clipboard = new Clipboard('.copy');