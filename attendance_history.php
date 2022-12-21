<?php include 'db_connect.php'; 
set_time_limit(350);
?>
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
				<form action="index.php?page=attendance_history" method="post">
					<div class="row justify-content-center">
						<label for="" class="mt-2">Month and Year of</label>
						<div class="col-sm-3">
							<input type="month" name="doc" id="doc" value="<?php echo date('Y-m') ?>" class="form-control" required>
						</div>
						<div class="col-sm-2">
							<button class="btn btn-primary" type="submit" name="filter" id="filter">Filter</button>
							
						</div>

					</div>

					<hr>
					<!-- <div style="float:right">
						<a href="javascript:void(0);" id="printPage" class="btn btn-success">Print</a>
							</div> -->
					<div class="row">
                    <div class="container-fluid" id="print">
								<table class='table table-bordered table-hover att-list' width="100%" border=1>
									<thead>
										<tr>
											<th class="text-center" width="5%">#</th>
											<th style="text-align:left">Student</th>
											<th style="text-align:left">Attendance</th>
											<th style="text-align:left">Date</th>
                                            <th style="text-align:left">Class</th>

										</tr>
									</thead>
						<?php
                        $i = 1;
						if (isset($_POST["filter"])) {
							// $class_id = $_POST['class_subject_id'];
							// $id = $_POST['id'];
							if (isset($_POST["doc"])) {
								$date = $_POST["doc"];
                                $month = date("m", strtotime($date));
                                $year = date("Y", strtotime($date));
                                echo $month;
							} else {
								$date = 0;
							}
							$sql_at = mysqli_query($conn, "SELECT * from attendance_list where month(doc) = '$month' AND year(doc) = '$year'");
							while($ress = mysqli_fetch_assoc($sql_at)){
							// if ($ress != null) {
							$a_id = $ress['id'];
                            $class_id = $ress['class_subject_id'];

							$sql_h = mysqli_query($conn, "SELECT cs.*, cl.*, sb.*, f.*, cu.* from class_subject cs, class cl, subjects sb, faculty f, courses cu where cs.id = $class_id and cl.id = cs.class_id and sb.id = cs.subject_id and f.id = cs.faculty_id and cu.id = cl.course_id");
							$result_h = mysqli_fetch_assoc($sql_h);
						?>

									<tbody>
										<?php

										// $ndate = date("m", strtotime($date));

										$sql_attendance = mysqli_query($conn, "SELECT * FROM attendance_record where attendance_id = $a_id");
										$res = mysqli_fetch_assoc($sql_attendance);
											$id = $res['student_id'];
											$sql_student = mysqli_query($conn, "SELECT * FROM students where id = $id");
											$result = mysqli_fetch_assoc($sql_student);
											if ($res['type'] == 0) {
                                                $date_a = $ress['doc'];
												$present = "Absent" . " ";
                                                $present .= "<a class='btn btn-sm btn-outline-danger' target='_blank' href='sendmail.php?student_id=$id&date=$date_a'>Send Nortification Mail</a>";
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
                                                <td><?php echo $result_h['course'] . " " . $result_h['subject'] . " [" . $result_h['name'] . "] " ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
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