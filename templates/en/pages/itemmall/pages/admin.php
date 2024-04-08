<div class="page">
	<div class="content_header border_box">
		<span class="latest_news vertical_center"> <a>Shop</a> &rarr; <i>Adding product</i></span>
	</div>
    <div class="page-body border_box self_clear">
		<!--BEGIN CONTENT-->
		<script language="javascript">
			function searchItem() {
				var ItemNameID = document.forms["searchID"]["ItemName"].value;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("itemSearch").innerHTML = xmlhttp.responseText;

					}
				}
				xmlhttp.open("GET", "<?= $TemplateUrl ?>actions/itemmall/search.php?name=" + ItemNameID, true);
				xmlhttp.send();
			}
		</script>
		<div id='icons' style="padding:10px; border: 2px solid grey; background: black;">
			<?
			$root = $_SERVER["DOCUMENT_ROOT"];
			$files = glob("$root/images/shop_icons/*.*");
			$img = isset($_GET["img"]) ? $_GET["img"] : "";
			for ($i = 1; $i < count($files); $i++) {
				$num = str_replace($root, "", $files[$i]);
				$selected = $num == $img ? 'border:2px solid yellow;' : '';
				echo "<a href='/?p=itemmall&admin&img=$num'><img src='$AssetUrl/$num' style='max-width:40px; $selected'></a>&nbsp;&nbsp;";
			}
			?>
		</div>
		<p><span style='color:red'>!IMPORTANT!</span> Select before the icon because the page will refresh and lost all next
			info</p>

		<form action="<?= $TemplateUrl ?>actions/itemmall/add-product.php?icon=<?= $img ?>" method="POST">
			<!--and here for GM is same... pratically we use numbers and at last diplay the title -->
			Category:
			<select name="category" id="category" style="margin: 5px; padding: 5px">

				<option selected='selected' value='7'>Equipments 15</option>
				<option value='1'>Weapons</option>
				<option value='2'>Accessories</option>
				<option value='3'>Mantles</option>
				<option value='4'>Costumes</option>
				<option value='5'>Build your own set</option>
				<option value='6'>Lapis</option>
				<option value='7'>Mounts</option>
				<option value='8'>Services</option>
				<option value='9'>Weapon Skins</option>


			</select>

			<br/>
			<br/>
			Product Name: <br><input maxlength="50" name="subject" type="text" id="subject" size="42"
									 placeholder="Enter Name from product (required)"/>
			<br/>
			<br/>
			Description (max 1000):<br/>
			<textarea rows="15" style='width:100%' name="message" maxlength="1000" id="message"></textarea>
			<br/>
			<br/>
			Price: <input maxlength="10" name="price" min='1' value='1' type="number" id="subject" size="5"/> SP
			<br/>
			<br>
			<style>
				.itemID {
					border: 0px;
					color: #000;
				}

				.itemID td {
					border: 0px;
					color: #000;
				}
			</style>
			<table class='itemID'>
				<?php for ($i = 1; $i <= 20; $i++) : ?>
				<tr>
					<td>
						<label>ItemID <?= $i ?></label> 
						<input type="number" value='0' name="<?= $i ?>" >
					</td>
					<td>
						<label>Count</label>
						<input style="width: 50px;" min='1' max='255' type='number' value='1' name="count<?= $i ?>">
					</td>
					<td>
						<label>Enchant</label>
						<input style="width: 50px;" min='0' max='20' type='number' value='0' name="item<?= $i ?>-enchant">
					</td>
					<?php for ($g = 1; $g <= 6; $g++) : ?>
					<td>
						<label>Gem <?= $g ?></label>
						<input style="width: 50px;" min='0' max='255' type='number' value='0' name="item<?= $i ?>-gem<?= $g ?>">
					</td>
					<?php endfor ?>
					<td class="text-center">
						<label>Can improve</label>
						<input style="width: 50px;" min='0' max='20' type='checkbox' value='0' name="item<?= $i ?>-improve">
					</td>
				</tr>
				<?php endfor ?>
			</table>
			<input type="submit" value="ADD PRODUCT" style="padding: 5px 10px; cursor: pointer; margin: 10px 0px 20px 350px;"/>

		</form>

		<form name="searchID" action="javascript:searchItem()">
			<strong style="font-size: 18px;">Search ItemID</strong>
			<br/>
			Item Name: <input maxlength="50" name="ItemName" type="text" value="" size="42"/>
			<input type="submit" value="search"/>
			<br/>
		</form>
		<div id='itemSearch'></div>
		<!--END CONTENT -->
    </div>
</div>