<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM subjects where id= " . $_GET['id']);
	foreach ($qry->fetch_array() as $k => $val) {
		$$k = $val;
	}
}
?>
<style>
	.filterDiv {

  display: none;
}
	.show {
		display: block;
	}


	/* Style the buttons */

	.btn:hover {
		background-color: #ddd;
	}

	.btn.active {
		background-color: #666;
		color: white;
	}
</style>
<div class="container-fluid">
	<div class="card-header">
		Subject Details
	</div>
	<div class="card-body">
		<input type="hidden" name="id">
		<div id="msg"></div>
		<div class="form-group">
			<label class="control-label">Subject</label>
			<input type="text" class="form-control" name="subject" value="<?php echo $subject ?>">
		</div>
		<div class="form-group">
			<label class="control-label">Description</label>
			<textarea name="description" id="" cols="30" rows="4" class="form-control"><?php echo $description ?></textarea>
		</div>
	</div>
	<div id="myBtnContainer">
		<button class="btn border: none; outline: none; background-color: #f1f1f1; cursor: pointer;" onclick="filterSelection('all')">All</button>
		<button class="btn border: none; outline: none; background-color: #f1f1f1; cursor: pointer;" onclick="filterSelection('upsr')">UPSR</button>
		<button class="btn border: none; outline: none; background-color: #f1f1f1; cursor: pointer;" onclick="filterSelection('upkk')">UPKK</button>
		<button class="btn border: none; outline: none; background-color: #f1f1f1; cursor: pointer;" onclick="filterSelection('prsa')"> PSRA</button>
	</div>
	<div class="container">
		<div class="filterDiv upsr">
			<table class="table table-condensed table-bordered table-hover">
				<thead>
					<h3><b>UPSR</b></h3>
					<tr>
						<th class="text-center">#</th>
						<th class="">ID #</th>
						<th class="">Name</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$sql = mysqli_query($conn, "SELECT * FROM students where class_id = 3");
					while ($row = mysqli_fetch_array($sql)) {
					?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td>
								<p> <b><?php echo $row['id_no'] ?></b></p>
							</td>
							<td>
								<p> <b><?php echo ucwords($row['name']) ?></b></p>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="filterDiv upkk">
		<table class="table table-condensed table-bordered table-hover">
				<thead>
					<h3><b>UPKK</b></h3>
					<tr>
						<th class="text-center">#</th>
						<th class="">ID #</th>
						<th class="">Name</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$sql = mysqli_query($conn, "SELECT * FROM students where class_id = 3");
					while ($row = mysqli_fetch_array($sql)) {
					?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td>
								<p> <b><?php echo $row['id_no'] ?></b></p>
							</td>
							<td>
								<p> <b><?php echo ucwords($row['name']) ?></b></p>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="filterDiv prsa">
		<table class="table table-condensed table-bordered table-hover">
				<thead>
					<h3><b>PRSA</b></h3>
					<tr>
						<th class="text-center">#</th>
						<th class="">ID #</th>
						<th class="">Name</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$sql = mysqli_query($conn, "SELECT * FROM students where class_id = 3");
					while ($row = mysqli_fetch_array($sql)) {
					?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td>
								<p> <b><?php echo $row['id_no'] ?></b></p>
							</td>
							<td>
								<p> <b><?php echo ucwords($row['name']) ?></b></p>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</div>
<script>
	filterSelection("all")

	function filterSelection(c) {
		var x, i;
		x = document.getElementsByClassName("filterDiv");
		if (c == "all") c = "";
		for (i = 0; i < x.length; i++) {
			w3RemoveClass(x[i], "show");
			if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
		}
	}

	function w3AddClass(element, name) {
		var i, arr1, arr2;
		arr1 = element.className.split(" ");
		arr2 = name.split(" ");
		for (i = 0; i < arr2.length; i++) {
			if (arr1.indexOf(arr2[i]) == -1) {
				element.className += " " + arr2[i];
			}
		}
	}

	function w3RemoveClass(element, name) {
		var i, arr1, arr2;
		arr1 = element.className.split(" ");
		arr2 = name.split(" ");
		for (i = 0; i < arr2.length; i++) {
			while (arr1.indexOf(arr2[i]) > -1) {
				arr1.splice(arr1.indexOf(arr2[i]), 1);
			}
		}
		element.className = arr1.join(" ");
	}

	// Add active class to the current button (highlight it)
	var btnContainer = document.getElementById("myBtnContainer");
	var btns = btnContainer.getElementsByClassName("btn");
	for (var i = 0; i < btns.length; i++) {
		btns[i].addEventListener("click", function() {
			var current = document.getElementsByClassName("active");
			current[0].className = current[0].className.replace(" active", "");
			this.className += " active";
		});
	}
</script>