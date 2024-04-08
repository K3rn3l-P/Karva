<?php
$type = isset($_GET["type"]) && is_numeric($_GET["type"]) ? GetClear($_GET["type"]) : 0;
?>
<div id="page-login" class="page page-login">
	<div class="content_header border_box">
		<?php
		$title = "Total Kills";
		switch ($type) {
			case 1:
				$title = "Weekly Kills";
				break;
			case 2:
				$title = "Daily Kills";
				break;
			case 3:
				$title = "Own Kills";
				break;	
			case 4:
				$title = "Death";
				break;	
		}
		?>
		<span class="latest_news vertical_center"> <a>PvP Ranking</a> &rarr; <i ><?= $title ?></i></span>
	</div>
    <div class="page-body border_box self_clear">
	
		<!--BEGIN CONTENT-->
		<div id="page-page" class="mainbox page page-page">
			<div class="mainbox-inner page-inner">
				<div class="mainbox-header page-header border_box">
					<div class="mainbox-header page-header border_box">

						<div style="margin: 15px 0 0 30px;float:right;">
							<?php
							switch ($type) {
								case 1:								
									$order = '[WK] DESC, [c].[K2] ASC, [c].[CharName] ASC';
									echo "
										<a class='nice_button' href='/?p=$page'>Total Kills</a>
										<a class='nice_button nice_active' href='/?p=$page&type=1'>Weekly</a>
										<a class='nice_button' href='/?p=$page&type=2'>Daily</a>
										<a class='nice_button' href='/?p=$page&type=3'>Own</a>
										<a class='nice_button' href='/?p=lastkills'>Last</a>
										";
									break;
									
								case 2:
									$order = '[DK] DESC, [c].[K2] ASC, [c].[CharName] ASC';
									echo "
										<a class='nice_button' href='/?p=$page'>Total Kills</a>
										<a class='nice_button' href='/?p=$page&type=1'>Weekly</a>
										<a class='nice_button nice_active' href='/?p=$page&type=2'>Daily</a>
										<a class='nice_button' href='/?p=$page&type=3'>Own</a>
										<a class='nice_button' href='/?p=lastkills'>Last</a>
										";
									break;
									
								case 3:
									$order = '[OwnKill] DESC, [c].[K2] ASC, [c].[CharName] ASC';
									echo "
										<a class='nice_button' href='/?p=$page'>Total Kills</a>
										<a class='nice_button' href='/?p=$page&type=1'>Weekly</a>
										<a class='nice_button' href='/?p=$page&type=2'>Daily</a>
										<a class='nice_button nice_active'  href='/?p=$page&type=3'>Own</a>
										<a class='nice_button' href='/?p=lastkills'>Last</a>
										";
									break;
								
								
								case 4:
									$order = '[K2] DESC, [c].[K1] ASC, [c].[CharName] ASC';
									echo "
										<a class='nice_button' href='/?p=$page'>Total Kills</a>
										<a class='nice_button' href='/?p=$page&type=1'>Weekly</a>
										<a class='nice_button' href='/?p=$page&type=2'>Daily</a>
										<a class='nice_button' href='/?p=$page&type=3'>Own</a>
										<a class='nice_button' href='/?p=lastkills'>Last</a>
										
										";
									break;	
									
									
								default:
									$order = '[c].[K1] DESC, [c].[K2] ASC, [c].[CharName] ASC';
									echo "
										<a class='nice_button nice_active' href='/?p=$page'>Total Kills</a>
										<a class='nice_button' href='/?p=$page&type=1'>Weekly</a>
										<a class='nice_button' href='/?p=$page&type=2'>Daily</a>
										<a class='nice_button' href='/?p=$page&type=3'>Own</a>
										<a class='nice_button' href='/?p=lastkills'>Last</a>
										";
									break;
							}
							?>
						</div>
						<br>
						<div style="clear:both;"></div>
<br>
					</div>
				</div>
				
			<br>
	
				
				<section id="statistics_title" style="margin-top: 10px;"></section>
				<section class="statistics_top" style="display:block;margin-top:20px;">
					<?php include('modules/engine.php') ?>
				</section>
				
				
			</div>
		</div>
		
		<!--END CONTENT -->
		
    </div>
</div>