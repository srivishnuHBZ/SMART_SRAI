<?php include('db_connect.php');

?>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Student</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_parent">
								<i class="fa fa-plus"></i> New Parent
							</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Student</th>
									<th class="">Parent Name(s)</th>
									<th ckass="">Email</th>
									<th>Attendance</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$student = $conn->query("SELECT * from parent");
								while ($row = $student->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<?php $sql_student = mysqli_query($conn, "SELECT * FROM students where id = " . $row['student_id']);
											$res = mysqli_fetch_array($sql_student);
											?>
											<p> <b><?php echo $res['name'] . " (" . $res['id_no'] . ")"; ?></b></p>
										</td>
										<td>
											<p> <b><?php echo "Father - " . $row['parent_name_f'] . "</br>" . "Mother - " . $row['parent_name_m']; ?></b></p>
										</td>
										<td>
											<p><b><?php echo $row['email'] ?></b></p>
										</td>
										<td>
										<button class="btn btn-sm btn-outline-primary view_attendance" type="button" data-id="<?php echo $row['student_id'] ?>">View Student Attendance</button>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-outline-primary edit_parent" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
											<button class="btn btn-sm btn-outline-danger delete_parent" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset
	}

	img {
		max-width: 100px;
		max-height: 150px;
	}
</style>
<script>
	$(document).ready(function() {
		$('table').dataTable()
	})
	$('#new_parent').click(function() {
		uni_modal("New Parent", "manage_parent.php", "")

	})

	$('.edit_parent').click(function() {
		uni_modal("Manage parent Details", "manage_parent.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.view_attendance').click(function() {
		uni_modal3("Student Attendance", "student_attendance.php?id=" + $(this).attr('data-id'), "")

	})
	$('.delete_parent').click(function() {
		_conf("Are you sure to delete this student?", "delete_parent", [$(this).attr('data-id')])
	})

	function delete_parent($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_parent',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>