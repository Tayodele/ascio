Dashboard = {};

Dashboard.Update = function(iMode){
  switch(iMode){
    case 1:
      var $data = $('#create-dash-form').serialize();
      $data += '&sAction=add_dashboard';
      $.ajax({
        url: "ajax/dash_main.php",
        dataType:"json",
        data: $data,
        success: function(oResult){
          if(oResult.sStatus == 'success')
            window.location.reload();
        }
      });
    break;
  }
};

Dashboard.LoadGraphs = function(aaDash){
	// Load the Visualization API.
    google.charts.load('current', {packages: ['corechart']});
    
    google.charts.setOnLoadCallback(drawChart);
	
    function drawChart() {
      var data = null;
      aaDash.forEach(function(dash,key){
        dash[3].forEach(function(graph,gkey) {
          var div = $('<div>',{'id': 'chart_div'+gkey, 'class': 'dush'});
          $('#dash_div'+key).append(div);
          data = new google.visualization.DataTable();
          data.addColumn(typeof(JSON.parse(graph[5]).aaData[0][0][0]), graph[3]);
          data.addColumn(typeof(JSON.parse(graph[5]).aaData[0][0][1]), graph[4]);
          data.addRows(JSON.parse(graph[5]).aaData[0]);
          var chart = null;
          var options = null;
          switch(graph[2]){
            case 'line':
              chart = new google.visualization.LineChart(document.getElementById('chart_div'+gkey));
              options = {width: 800, height: 400,title: graph[1],vAxis:{scaleType: graph[9], title: graph[4]},hAxis:{scaleType: graph[8], title: graph[3]}};
              break;
            case 'column':
              chart = new google.visualization.ColumnChart(document.getElementById('chart_div'+gkey));
              options = {width: 800, height: 400,title: graph[1],vAxis:{scaleType: graph[9], title: graph[4]},hAxis:{scaleType: graph[8], title: graph[3]}};
              break;
            case 'pit':
              chart = new google.visualization.PieChart(document.getElementById('chart_div'+gkey));
              options = {width: 800, height: 400,title: graph[1],vAxis:{scaleType: graph[9], title: graph[4]},hAxis:{scaleType: graph[8], title: graph[3]}};
              break;
            case 'scatter':
              chart = new google.visualization.ScatterChart(document.getElementById('chart_div'+gkey));
              options = {
                        width: 800, 
                        height: 400,
                        title: graph[1],
                        crosshair: { trigger: "both", orientation: "both" },
                        trendlines: {
                          0: {
                            type: 'polynomial',
                            degree: 1,
                            visibleInLegend: true
                          }
                        },
                        vAxis: {scaleType: graph[9], title: graph[4]},hAxis:{scaleType: graph[8], title: graph[3]}
                        };
          }
          chart.draw(data, options);
        });
      });
    }
  		
};

Dashboard.LoadDashboards = function(bExample = null){
    var $data = "sAction=load_active_boards"
	if(bExample == true) {
    $data +='&bExample=1';
	}
	
	$.ajax({
        url: "ajax/getData.php",
        dataType:"json",
        data: $data,
        success: function(oResult){
        	var div = null;
        	oResult.Dashboards.forEach(function(dashboard,key){
          	div = $('<div>',{'id': 'dash_div'+(key), 'class': 'dashy'});
            $('#main1').append(div);
            div = $('<div>',{class: 'dropdown', id:'dash_d'+(key)});
            $('#dash_div'+(key)).append(div);
            div = $('<button>',{id: 'head'+(key), class: 'dash-headers dropdown-toggle', type: 'link', 'data-toggle': 'dropdown'}).text(dashboard[1]);
            $('#dash_d'+(key)).append(div);
            div = $('<ul>',{class:"dropdown-menu", id:'menu'+(key)})
            $('#dash_d'+(key)).append(div);
            div= $('<li>');
            $('#menu'+(key)).append(div);
            div = $('<a>',{onclick: "Dashboard.DeleteDash("+dashboard[0]+")"}).text('Delete Dashboard');
            $('#menu'+(key)).find("li").append(div);
            div = $('<div>',{'class': 'desc', 'text': dashboard[2]});
            $('#dash_div'+(key)).append(div);
        	});
        	oResult.Empties.forEach(function(empty,key){
      			div = $('<div>',{'id': 'dash_div'+(key+oResult.Dashboards.length), 'class': 'dashy'});
      			$('#main1').append(div);
            div = $('<div>',{class: 'dropdown', id:'dash_d'+(key+oResult.Dashboards.length)});
            $('#dash_div'+(key+oResult.Dashboards.length)).append(div);
  				  div = $('<button>',{id: 'head'+(key+oResult.Dashboards.length), class: 'dash-headers dropdown-toggle', type: 'link', 'data-toggle': 'dropdown'}).text(empty[1]);
            $('#dash_d'+(key+oResult.Dashboards.length)).append(div);
            div = $('<ul>',{class:"dropdown-menu", id:'menu'+(key+oResult.Dashboards.length)})
            $('#dash_d'+(key+oResult.Dashboards.length)).append(div);
            div= $('<li>');
            $('#menu'+(key+oResult.Dashboards.length)).append(div);
            div = $('<a>',{onclick: "Dashboard.DeleteDash("+empty[0]+")"}).text('Delete Dashboard');
            $('#menu'+(key+oResult.Dashboards.length)).find("li").append(div);
      			$('#dash_div'+(key+oResult.Dashboards.length)).append('<p>There are no graphs for this dashboard</p>');
          });
        	console.log('success');
        	Dashboard.LoadGraphs(oResult.Dashboards);
					
					//load UI creation options
					$('#new-dash').load('scripts/new.html');
        }
  });
};

Dashboard.DeleteDash = function(iId) {
  var $data  = 'sAction=delete_dash&iId='+iId;
  $.ajax({
	url: "ajax/dash_main.php",
	dataType:"json",
	data: $data,
	success: function(oResult){
	  if(oResult.sStatus == 'success'){
		window.location.reload();
	  } else {
		console.log('error:'+oResult.sMessage);
	  }
	}
  });
};