<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
</head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script>
		$.ajax({
			type: "POST",
			url: "http://localhost/blabla/",
			data: {
				action: 'add_merchant',
				user_id: 2,
				merchant_name: '7-11'
			},
			success: function(data){
				console.log(data);
			}
		});
	</script>
</body>
</html>