	<header>
		<ul class="action-buttons clearfix fr">
			<li>
				<a href="/documentation/" class="button button-gray" rel="#overlay">
					Изход
				</a>
			</li>
			<li>
				<!--
				<a href="/documentation/" class="button button-gray no-text help" rel="#overlay">
					
					<span class="help"></span>
					
				</a>
				-->
			</li>
		</ul>
		<h2>
			<a href="/members.php">Към КСК 2013</a>
		</h2>
		<table class="datatable paginate sortable full">
			<tbody>
				<tr>
					<td style="width: 600px;">
						<ul>
							<button onclick="window.location = 'members.php';" class="button button-blue">Начало</button>
							<button class="button button-blue dropDown">Номенклатури<span style="margin-top: 4px" class="arrow-down"></span></button>
							<ul class="dropDownMenu" style="display: none;position:absolute">
								<li><button onclick="window.location = 'user.php';" class="button button-blue">Потребители</button></li>
							</ul>
							<button onclick="window.location = '/members.php';" class="button button-blue">Към КСК 2013</button>
							<a href="/online_apply/" class="button button-blue">Online Плащания</a>
						</ul>
					</td>
					<td>
					<!--
						<?php/*
						if (is_user_admin($_SESSION ['username'], $_SESSION ['pass'])) {
							echo "<button onclick='window.location=\"/admin/members.php\"' name='adminBtn' id='adminBtn' class='button button-red'>Администратор</button></td>";
							echo "<td><button class='button button-red' id='moderatorBtn' name='adminBtn' onclick='window.location=\"/moderator/members.php\"'>Модератор</button>";
						} elseif (is_user_moderator($_SESSION ['username'], $_SESSION ['pass'])) {
							echo "<button class='button button-red' id='moderatorBtn' name='adminBtn' onclick='window.location=\"/moderator/members.php\"'>Модератор</button>";
						} else {
							echo '';
						}*/
						?>
					-->
					</td>
				</tr>
			</tbody>
		</table>
	</header>