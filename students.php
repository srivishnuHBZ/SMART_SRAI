<?php include('db_connect.php'); 
set_time_limit(3600);
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
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_student">
								<i class="fa fa-plus"></i> New Student
							</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">ID #</th>
									<th class="">Name</th>
									<th class="">Class</th>
									<th>Parent & Address Information</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$student = $conn->query("SELECT s.*,concat(co.course,' ',c.level,'-',c.section) as `class` FROM students s inner join `class` c on c.id = s.class_id inner join courses co on co.id = c.course_id order by s.id asc ");
								while ($row = $student->fetch_assoc()) :
									$id = $row['id'];
									$parent = $conn->query("SELECT * FROM parent where student_id = $id");
									$res = mysqli_fetch_array($parent);
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<p> <b><?php echo $row['id_no'] ?></b></p>
										</td>
										<td>
											<p> <b><?php echo ucwords($row['name']) ?></b></p>
										</td>
										<td class="">
											<p> <b><?php echo $row['class'] ?></b></p>
										</td>
										<?php if ($res != null) { ?>
											<td class="">
												<button class="btn btn-sm btn-outline-success view_parent" type="button" data-id="<?php echo $res['id'] ?>" >View</button>
												<a class="btn btn-sm btn-outline-danger" target="_blank" href="sendmail.php?student_id=<?php echo $row['id'] ?>">Send Nortification Mail</a>
											</td>
										<?php } else { ?>
											<td class="">
												<p> <b><?php echo "No Parent Data Available" ?></b></p>
											</td>
										<?php } ?>
										<td class="text-center">
											<button class="btn btn-sm btn-outline-primary edit_student" type="button" data-id="<?php echo $row['id'] ?>">Edit</button>
											<button class="btn btn-sm btn-outline-danger delete_student" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	$('#new_student').click(function() {
		uni_modal("New student", "manage_student.php", "")

	})

	$('.edit_student').click(function() {
		uni_modal("Manage student Details", "manage_student.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.view_parent').click(function() {
		uni_modal("View Parent Details", "view_parent.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.delete_student').click(function() {
		_conf("Are you sure to delete this student?", "delete_student", [$(this).attr('data-id')])
	})

	function delete_student($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_student',
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