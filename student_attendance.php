<?php
include('db_connect.php');
session_start();
if (isset($_GET['id'])) {
	$student_id = $_GET['id'];
}
?>
<?php include 'db_connect.php'; ?>
<script lang='javascript'>
	$(document).ready(function() {
		$('#printPage').click(function() {
			var data = '<input type="button" value="Print this page" onClick="window.print()">';
			data += '<div class="container-fluid" id="div_print">';
			data += $('#print').html();
			data += '</div>';

			myWindow = window.open('', '', '');
			myWindow.innerWidth = screen.width;
			myWindow.innerHeight = screen.height;
			myWindow.screenX = 0;
			myWindow.screenY = 0;
			myWindow.document.write(data);
			myWindow.focus();
		});
	});
</script>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header"><b>Attendance Check</b></div>
			<div class="card-body">


				<hr>
				<!-- <div style="float:right">
					<a href="javascript:void(0);" id="printPage" class="btn btn-success">Print</a>
				</div> -->
				<div class="row">

					<table class='table table-bordered table-hover att-list' width="100%" border=1>
						<thead>
							<tr>
								<th class="text-center" width="5%">#</th>
								<th style="text-align:left">Student</th>
								<th style="text-align:left">Attendance</th>
								<th style="text-align:left">Date</th>
								<th style="text-align:left">Details</th>

							</tr>
						</thead>

						<tbody>
							<?php
							$i = 1;
							$sql_at = mysqli_query($conn, "SELECT ar.* ,al.* from attendance_record ar, attendance_list al where ar.student_id = $student_id and al.id = ar.attendance_id");
							while ($ress = mysqli_fetch_assoc($sql_at)) {
								// if ($ress != null) {
								$class_id = $ress['class_subject_id'];

								$sql_h = mysqli_query($conn, "SELECT cs.*, cl.*, sb.*, f.*, cu.* from class_subject cs, class cl, subjects sb, faculty f, courses cu where cs.id = $class_id and cl.id = cs.class_id and sb.id = cs.subject_id and f.id = cs.faculty_id and cu.id = cl.course_id");
								$result_h = mysqli_fetch_assoc($sql_h);

								// $ndate = date("m", strtotime($date));

								$sql_attendance = mysqli_query($conn, "SELECT * FROM attendance_record where student_id = $student_id");
								$res = mysqli_fetch_assoc($sql_attendance);
								// $id = $res['student_id'];
								$sql_student = mysqli_query($conn, "SELECT * FROM students where id = $student_id");
								$result = mysqli_fetch_assoc($sql_student);
								if ($res['type'] == 0) {
									$present = "Absent";
								} elseif ($res['type'] == 1) {
									$present = "Present";
								} elseif ($res['type'] == 2) {
									$present = "Late";
								}
							?>
								<tr>
									<td style="text-align:center"><?php echo $i++; ?></td>
									<td><?php echo $result['name'] ?></td>
									<td><?php echo $present ?></td>
									<td><?php echo $res['date_created'] ?></td>
									<td><?php echo $result_h['course'] . ' ' . $result_h['subject'] . ' [ ' . $result_h['name'] . ' ]'?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- <div class="col-md-12"  style="display: none" id="submit-btn-field">
							<center>
								<button class="btn btn-success btn-sm col-sm-3" type="button" id="print_att"><i class="fa fa-print"></i> Print</button>
							</center>
						</div> -->
		</div>
	</div>
</div>
</div>