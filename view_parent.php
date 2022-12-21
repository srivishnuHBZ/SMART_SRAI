<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM parent where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-parent">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
            <label for="" class="control-label">Student</label>
            <select name="student_id" id="" class="custom-select select2" disabled>
                <option value=""></option>
                <?php
                $class = $conn->query("SELECT * from students");
                while($row=$class->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($student_id) && $student_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name']  . "(" . $row['id_no'] . ")"; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Father Name</label>
            <input type="text" class="form-control" name="parent_name_f"  value="<?php echo isset($parent_name_f) ? $parent_name_f :'N/A' ?>" readonly/>
            <small><i><b>*Please Leave It As N/A If No Data For Father</b></i></small>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Mother Name</label>
            <input type="text" class="form-control" name="parent_name_m"  value="<?php echo isset($parent_name_m) ? $parent_name_m :'N/A' ?>" readonly/>
            <small><i><b>*Please Leave It As N/A If No Data For Mother</b></i></small>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea rows = "5" class="form-control" name="address" readonly><?php echo isset($address) ? $address :'' ?></textarea>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Contact No 1</label>
            <input type="text" class="form-control" name="tel_no_1"  value="<?php echo isset($tel_no_1) ? $tel_no_1 :'' ?>" readonly/>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Contact No 2</label>
            <input type="text" class="form-control" name="tel_no_2"  value="<?php echo isset($tel_no_2) ? $tel_no_2 :'' ?>" readonly/>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="text" class="form-control" name="email"  value="<?php echo isset($email) ? $email :'' ?>" readonly/>
        </div>
    </form>
</div>
<script>
    $('#manage-parent').on('reset',function(){
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#manage-parent').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_parent',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully saved.",'success')
                        setTimeout(function(){
                            location.reload()
                        },1000)
                }else if(resp == 2){
                $('#msg').html('<div class="alert alert-danger mx-2">ID # already exist.</div>')
                end_load()
                }   
            }
        })
    })

    $('.select2').select2({
        placeholder:"Please Select here",
        width:'100%'
    })
</script>