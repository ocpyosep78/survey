<?php
	# collect data script

	// paths to predefined functions
	include_once '../include/function.php';
	include_once '../payment/global.php';
	
	// export the names of the users paid online
	
	$online_paid = mysql_query( "
			
		SELECT * 
		FROM users
		INNER JOIN payment_requests ON users.id = payment_requests.user_id
		ORDER BY payment_requests.id ASC 
	
	") or die( mysql_error() );
	
#	$online_paid_user = mysql_fetch_array( $online_paid );        
?>