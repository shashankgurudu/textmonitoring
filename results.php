<html>
<?php
session_start(); // Starting Session
{
$username=$_POST['username'];
?>
<head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      var total;
      var Positive = 0,Negative = 0,Neutral = 0;
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        var searchterm = <?php $username ?>;

        var data_file = "http://52.36.166.210:9200/ + 'searchterm' + /_search?size=1000&q=*:*";
            var http_request = new XMLHttpRequest();
            try{
               // Opera 8.0+, Firefox, Chrome, Safari
               http_request = new XMLHttpRequest();
            }catch (e){
               // Internet Explorer Browsers
               try{
                  http_request = new ActiveXObject("Msxml2.XMLHTTP");
          
               }catch (e) {
        
                  try{
                     http_request = new ActiveXObject("Microsoft.XMLHTTP");
                  }catch (e){
                     // Something went wrong
                     alert("Your browser broke!");
                     return false;
                  }
          
               }
            }

            http_request.onreadystatechange = function(){
      
               if (http_request.readyState == 4  ){
                  // Javascript function JSON.parse to parse JSON data
                  var jsonObj = JSON.parse(http_request.responseText);

                  total = jsonObj.hits.total;
                  for (var i = 0; i < total; i++) {
                    console.log("hit");
                    if ((jsonObj.hits.hits[i]._source.sentiment) == "positive") {
                        Positive++;
                    }
                    else if ((jsonObj.hits.hits[i]._source.sentiment) == "negative") {
                        Negative++;
                    }
                    else if ((jsonObj.hits.hits[i]._source.sentiment) == "neutral") {
                        Neutral++;
                    }
                  }

                  data.addColumn('string', 'Topping');
                  data.addColumn('number', 'Slices');
                  data.addRows([
                    ['Postive', Positive],
                    ['Negative', Negative],
                    ['Neutral', Neutral]
                  ]);

        // Set chart options
                  var options = {'title':'How Much Pizza I Ate Last Night',
                                 'width':400,
                                 'height':300};

                  // Instantiate and draw our chart, passing in some options.
                  var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                  chart.draw(data, options);


                  // jsonObj variable now contains the data structure and can
                  // be accessed as jsonObj.name and jsonObj.country.
              
               }
            }
      
            http_request.open("GET", data_file, true);
            http_request.send();

              }
              //setInterval(drawChart, 2000);
    </script>
  </head>
<body>


 <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
</body>
</html>