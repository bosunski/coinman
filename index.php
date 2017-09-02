<!DOCTYPE html>
<html>
<head>
	<title>coinman</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
</head>
<body>

<div id="load-results"></div>

<script type="text/javascript">
	$("document").ready(function (){
		// dummy data
		var data = "all";

		$.ajax({
			type: "GET",
			url: "data.php",
			dataType: "json",
			data: {
				data:data
			},
			cache:false,
			success: function(data){
				// data is already on json object
				
				//console.log(data['buys']);
				//console.log(data['sales']);
			}
		});
	});
</script>
</body>
</html>