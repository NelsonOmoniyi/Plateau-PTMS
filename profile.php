<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();
//var_dump($_SESSION);
$username  = $_SESSION['username_sess'];
$user      = $dbobject->db_query("SELECT * FROM userdata WHERE username='$username'");
?>
  <div class="row">
        <div class="col-md-4 col-xl-3" id="photo_display">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Photo</h5>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo $_SESSION['photo_path_sess']; ?>" id="avatar_profile" alt=""
                        class="img-fluid rounded-circle mb-2" width="128" height="128" />

                    <h5 class="card-title mb-0"><?php echo $user[0]['firstname'] . " " . $user[0]['lastname'] ?></h5>
                    <div class="text-muted mb-2"><?php echo $_SESSION['role_id_name'] ?></div>
                    <div>
                        <button class="btn btn-file border-primary">
                            <div id="fileuploader"></div>
                        </button>
                        <div id="photo_response" style="color:green;display:none">Click on 'Save Changes' button to
                            accept changes</div>
                        <!--										<a class="btn btn-primary btn-block" href="#"><span data-feather="message-square"></span> Upload Photo</a>-->
                    </div>

                </div>
                <hr class="my-0" />


            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <div class="card-actions float-right">
                        <div class="dropdown show">

                        </div>
                    </div>
                    <h5 class="card-title mb-0">Bio Data</h5>
                </div>
                <div class="card-body h-100">
                    <form action="" id="form1" autocomplete="off">
                        <input type="hidden" name="op" value="Users.profileEdit">
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <input type="hidden" name="photo" id="photo" value="<?php echo $_SESSION['photo_file_sess'] ?>">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">First Name<span class="asterik">*</span></label>
                                    <input type="text" name="firstname" value="<?php echo $user[0]['firstname'] ?>"
                                        class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Last Name<span class="asterik">*</span></label>
                                    <input type="text" name="lastname" value="<?php echo $user[0]['lastname'] ?>"
                                        class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Phone Number<span class="asterik">*</span></label>
                                    <input type="number" name="mobile_phone"
                                        value="<?php echo $user[0]['mobile_phone'] ?>" class="form-control"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Gender<span class="asterik">*</span></label>
                                    <select class="form-control" name="sex" id="sex">
                                        <option value="male"
                                            <?php echo ($user[0]['sex'] == "male") ? "selected" : ""; ?>>Male</option>
                                        <option value="female"
                                            <?php echo ($user[0]['sex'] == "female") ? "selected" : ""; ?>>Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3">
                                <a href="javascript:saveRecord()" class="btn btn-md btn-primary" style="color:#fff">
                                    Save Changes
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <link rel="stylesheet" href="css/uploadfile.css">
<script src="js/jquery.uploadfile.min.js"></script>
    <script>
$(document).ready(function() {
    $("#fileuploader").uploadFile({
        url: "upload.php",
        fileName: "upfile",
        showPreview: false,
        uploadStr: "Upload Photo",
        statusBarWidth: "50%",
        previewHeight: "100px",
        previewWidth: "100px",
        allowedTypes: "jpg,png",
        maxFileSize: 1000000,
        onSelect: function(files) {
            $("#photo_response").css('display', 'none');
            return true; //to allow file submission.
        },
        onSubmit: function(files) {
            $("#photo_display").block({
                message: 'processing image'
            });

            //files : List of files to be uploaded
            //return flase;   to stop upload
        },
        onSuccess: function(files, data, xhr, pd) {
            $("#photo_display").unblock();
            var resss = JSON.parse(data);
            if (resss.response_code == 0) {
                $("#photo").val(resss.data.file + "." + resss.data.ext);
                $("#avatar_profile").attr('src', 'img/profile_photo/' + resss.data.file + "." +
                    resss.data.ext);
                $("#photo_response").css('display', 'block');
            } else {
                $("#photo_response").css('display', 'block');
                $("#photo_response").css('color', 'red');
                $("#photo_response").text(resss.response_message);
            }
        }
    });
});

function saveRecord() {
    $.blockUI();
    $("#save_facility").text("Loading......");
    var dd = $("#form1").serialize();
    $.post("utilities.php", dd, function(re) {
        $.unblockUI();
        $("#save_facility").text("Save");
        console.log(re);
        if (re.response_code == 0) {
            alert(re.response_message);
        } else
            regenerateCORS();
        alert(re.response_message)
    }, 'json')
}
</script>
