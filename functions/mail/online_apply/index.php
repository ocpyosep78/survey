<?php
/*
if (!isset($_SESSION)) {
    session_start();
}
include_once '../include/function.php';
header('Content-Type: text/html; charset=utf-8');
if (date('H:i:s') >= START_WORK && date('H:i:s') <= END_WORK) {
    if (isset($_COOKIE ['ksk'])) {
        include_once '../autologin.php';
    }
    if (isset($_SESSION ['logged'])) {
        if (isSet($_POST ['submit'])) {
            if (isSet($_POST['submit'])) {
                require_once '../action.php';
            }
*/			
	# collect data script
	require_once ('../online_apply/functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html lang="en" xmlns="http://www.w3.org/1999/xhtml">
        
		<?php
			require_once ('head.php');
		?>
        
		<body style="zoom: 1;">
            <div id="test"></div>
            <div id="wrapper">
                <section id='mainSection'>
                    <div class="container_8 clearfix">
                        <!-- Main Section -->
                        <section class="main-section grid_8">
                            <div class="main-content">
                                
								<?php
									require_once ('header.php');
								?>
								
								<section class="container_6 clearfix">
                                    <div class="grid_7">
                                        <h3>Online плащания КСК 2013</h3>
                                        <hr>
										<section>
											<div>
												
												<form action="functions.php" method="POST">
													<table class="datatable paginate sortable full">
														<thead>
															<tr>
																<th>Входящ номер</th>
																<th style="width: 60%">Kандидат-студент</th>
																<th>ЕГН</th>
																<th>Одобрен/а</th>
															</tr>
														</thead>
														<tbody>
															<?php
																// initialize the loop to display the online-paid appliers
																while( $online_paid_user = mysql_fetch_array( $online_paid ) )
																{
															?>
																<tr>
																	<td class="ac">
																		<?php echo $online_paid_user['payment_requests.id'];?>
																	</td>
																	<td>
																		<a target="_blank" href="<?php echo 'https://online.uni-sofia.bg/uploads/id_' . $online_paid_user["id"] . '_' . $online_paid_user["egn"]; ?>">
																			<?php
																				// display the first name
																				echo $online_paid_user['name'] ;
																				echo ' ';
																				// display the surname
																				echo $online_paid_user['surname'] ;
																				echo ' ';
																				// display the last name
																				echo $online_paid_user['family'] ;
																			?>	
																		</a>
																	</td>
																	<td class="ac">
																	<?php
																		// display the id number
																		echo $online_paid_user['egn'] ;
																	?>
																	</td>
																	<td class="ac">
																		<input type="checkbox"
																		name="
																			<?php
																				// initialize the id number as name to the form
																				echo $online_paid_user['egn'] ;
																			?>
																		"
																		/>
																	</td>
																</tr>
															<?php
																}
															?>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="4">
																	<table class="full ac">
																		<tr>
																			<td>
																				<input class="button button-green" name="confirm" type="submit" value="Одобри" style="color:#fff" />
																			</td>
																			<td>
																				<input class="button button-red" name="confirm" type="reset" value="Изчисти" />
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tfoot>
													</table>
												</form>
												
											</div>
										</section>
                                    </div>
								</section>
                                
								<p id="show_region"></p>
<?php      
            require_once '../footer.php';
            mysql_close();
/*
		} else {
			header('location: /members.php');
        }
    } else {
        header('location: /members.php');
    }
} else {
    header("Location: /index.php");
}
*/
?>