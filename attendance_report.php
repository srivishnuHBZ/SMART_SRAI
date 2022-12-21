<?php include 'db_connect.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
			<div class="card-header"><b>Attendance Report</b></div>
			<div class="card-body">
				<form action="index.php?page=attendance_report" method="post">
					<div class="row justify-content-center">
						<label for="" class="mt-2">Date of</label>
						<div class="col-sm-3">
							<input type="date" name="doc" id="doc" value="" class="form-control">
						</div>
						<div class="col-sm-2">
							<button class="btn btn-primary" type="submit" name="filter" id="filter">Filter</button>

						</div>
						<label for="" class="mt-2">Student</label>
						<div class="col-sm-3">
							<select class="form-control student" name="student" id="student">
								<option value="0">Select Student</option>
								<?php $sql_student = mysqli_query($conn, "SELECT * FROM students");
								while ($result = mysqli_fetch_array($sql_student)) {
									$id = $result['id'];
									$student_id = $result['id_no'];
									$student_name = $result['name'];
									$display = "[" . $student_id . "] " . $student_name;
									echo "<option value='$id'>$display</option>";
								}
								?>
							</select>
							<script>
								$(document).ready(function() {
									$("#student").select2();
								});
							</script>
						</div>
						<div class="col-sm-2">
							<button class="btn btn-primary" type="submit" name="filter_s" id="filter_s">Filter</button>

						</div>
						<div style="float:right">
							<a href="javascript:void(0);" id="printPage" class="btn btn-success">Print</a>
						</div>
					</div>

					<hr>

					<div class="row">
						<?php
						if (isset($_POST["filter"])) {
							$i = 1;
							// $class_id = $_POST['class_subject_id'];
							// $id = $_POST['id'];
							if (isset($_POST["doc"])) {
								$date = $_POST["doc"];
							} else {
								$date = 0;
							}

						?>
							<div class="container-fluid" id="print">
								<?php
								$sql_at = mysqli_query($conn, "SELECT * from attendance_list where doc = '$date'");
								while ($ress = mysqli_fetch_assoc($sql_at)) {
									// if ($ress != null) {
									$a_id = $ress['id'];
									$class_id = $ress['class_subject_id'];

									$sql_h = mysqli_query($conn, "SELECT cs.*, cl.*, sb.*, f.*, cu.* from class_subject cs, class cl, subjects sb, faculty f, courses cu where cs.id = $class_id and cl.id = cs.class_id and sb.id = cs.subject_id and f.id = cs.faculty_id and cu.id = cl.course_id");
									$result_h = mysqli_fetch_assoc($sql_h);
								?>
									<table width="100%">
										<tr>
											<td width="50%">
												<p>Subject: <b class="subject"><?php echo $result_h['subject']  ?></b></p>
												<p>Course: <b class="course"><?php echo $result_h['course'] ?></b></p>
												<!-- <p>Total Days of Classes: <b class="noc"></b></p> -->
											</td>
											<td width="50%">
												<p>Month of: <b class="doc"><?php echo $date; ?></b></p>
												<p>Faculty :<b class="class"><?php echo $result_h['name'] ?></b></p>

											</td>
										</tr>
									</table>
									<table class='table table-bordered table-hover att-list' width="100%" border=1>
										<thead>
											<tr>
												<th class="text-center" width="5%">#</th>
												<th style="text-align:left">Student</th>
												<th style="text-align:left">Attendance</th>
												<th style="text-align:left">Date</th>

											</tr>
										</thead>

										<tbody>
											<?php

											$ndate = date("m", strtotime($date));

											$sql_attendance = mysqli_query($conn, "SELECT * FROM attendance_record where attendance_id = $a_id");
											while ($res = mysqli_fetch_assoc($sql_attendance)) {
												$id = $res['student_id'];
												$sql_student = mysqli_query($conn, "SELECT * FROM students where id = $id");
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
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } ?>
							</div>
					</div>
				<?php	} ?>
				<?php
						if (isset($_POST["filter_s"])) {
							$i = 1;
							// $class_id = $_POST['class_subject_id'];
							// $id = $_POST['id'];
							if (isset($_POST["student"])) {
								$student = $_POST["student"];
							}
							// echo $student;	

						?>
							<div class="container-fluid" id="print">
								<?php
								$sql_at = mysqli_query($conn, "SELECT * from attendance_record where student_id = $student");
								while ($ress = mysqli_fetch_assoc($sql_at)) {
									// if ($ress != null) {
									$a_id = $ress['attendance_id'];
									// echo $a_id;
									$sql_a_r = mysqli_query($conn, "SELECT al.*, ar.* from attendance_list al, attendance_record ar where al.id = $a_id");
									$res_a_r = mysqli_fetch_array($sql_a_r);
									$class_id = $res_a_r['class_subject_id'];

									$sql_h = mysqli_query($conn, "SELECT cs.*, cl.*, sb.*, f.*, cu.* from class_subject cs, class cl, subjects sb, faculty f, courses cu where cs.id = $class_id and cl.id = cs.class_id and sb.id = cs.subject_id and f.id = cs.faculty_id and cu.id = cl.course_id");
									$result_h = mysqli_fetch_assoc($sql_h);
								?>
									<table width="100%">
										<tr>
											<td width="50%">
												<p>Subject: <b class="subject"><?php echo $result_h['subject']  ?></b></p>
												<p>Course: <b class="course"><?php echo $result_h['course'] ?></b></p>
												<!-- <p>Total Days of Classes: <b class="noc"></b></p> -->
											</td>
											<td width="50%">
												
												<p>Faculty :<b class="class"><?php echo $result_h['name'] ?></b></p>
												<p>&nbsp;<b class="doc"></b></p>

											</td>
										</tr>
									</table>
									<table class='table table-bordered table-hover att-list' width="100%" border=1>
										<thead>
											<tr>
												<th class="text-center" width="5%">#</th>
												<th style="text-align:left">Student</th>
												<th style="text-align:left">Attendance</th>
												<th style="text-align:left">Date</th>

											</tr>
										</thead>

										<tbody>
											<?php

											// $ndate = date("m", strtotime($date));

											$sql_attendance = mysqli_query($conn, "SELECT * FROM attendance_record where student_id = $student");
											$res = mysqli_fetch_assoc($sql_attendance);
												// $id = $res['student_id'];
												$sql_student = mysqli_query($conn, "SELECT * FROM students where id = $student");
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
												</tr>
										</tbody>
									</table>
								<?php } ?>
							</div>
					</div>
				<?php	} ?>

				<!-- <div class="col-md-12"  style="display: none" id="submit-btn-field">
							<center>
								<button class="btn btn-success btn-sm col-sm-3" type="button" id="print_att"><i class="fa fa-print"></i> Print</button>
							</center>
						</div> -->
			</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#manage-attendance').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_attendance',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved.", 'success')
					setTimeout(function() {
						location.reload()
					}, 1000)
				} else if (resp == 2) {
					alert_toast("Class already has an attendance record with the slected subject and date.", 'danger')
					end_load();
				}
			}
		})
	})
	$('#print_att').click(function() {
		var _c = $('#att-list').html();
		var ns = $('noscript').clone();
		var nw = window.open('', '_blank', 'width=900,height=600')
		nw.document.write(_c)
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	})
</script>