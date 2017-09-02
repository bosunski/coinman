<!DOCTYPE html>
<html>
<head>
	<title>Load Coinman</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>



<script type="text/javascript">
	$("document").ready(function (){
		var data = 'all';
		$.ajax({
			type: "GET",
			url: "class/load-data.php",
			dataType: "json",
			data: {
				data: data
			},
			success: function(data){
				// here data is alread in Json from the dataType set
			}
		});
	});

</script>
</body>
</html>
