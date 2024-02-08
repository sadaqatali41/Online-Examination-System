<?php
include 'class/config.php';
include 'class/database.php';
include 'class/userAuth.php';
$config = new Config();
$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$auth->sessionStart();
if ($auth->loginCheck($conn) === FALSE) {
  header("Location: login.php");
}
$user_data = $_SESSION['user_data'];

$stp = $conn->prepare("SELECT es.* FROM email_settings es WHERE 1=1");
$stp->execute();
$resp = $stp->get_result();
$stp->close();
$rowp = $resp->fetch_assoc();

#CC email
$stcc = $conn->prepare("SELECT es.* FROM cc_email es WHERE 1=1");
$stcc->execute();
$rescc = $stcc->get_result();
$stcc->close();
?>

<!-- inlude header -->
<?php include_once 'inc/header.php'; ?>
<!-- content wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Email Settings</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Email Settings</li>
        </ol>
    </section>
    <!-- main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="box box-info">
                    <div class="box-body">
                        <form id="emailSettingForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" id="emailSettingFormBtn" class="btn btn-primary btn-sm pull-right">Save</button>
                                </div>
                            </div>
                            <div class="row no-pad">
                                <div class="col-md-8">
                                    <div class="form-wrapper">
                                        <div class="row">                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email_subject">Email Subject</label>
                                                    <input type="text" id="email_subject" name="email_subject" value="<?= $rowp['email_subject']; ?>" class="form-control" placeholder="Email Subject" autocomplete="off">
                                                    <input type="hidden" name="es_id" value="<?php echo $rowp['id']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="host_name">Host Name</label>
                                                    <input type="text" id="host_name" name="host_name" value="<?= $rowp['host_name']; ?>" class="form-control" placeholder="Host Name" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="port_no">Port No</label>
                                                    <input type="text" id="port_no" name="port_no" value="<?= $rowp['port_no']; ?>" class="form-control" placeholder="Port No" autocomplete="off">
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="user_name">User Name</label>
                                                    <input type="text" id="user_name" name="user_name" value="<?= $rowp['user_name']; ?>" class="form-control" placeholder="User Name" autocomplete="off">
                                                </div>
                                            </div>                                                                                               
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="text" id="password" name="password" value="<?= $rowp['password']; ?>" class="form-control" placeholder="Password" autocomplete="off">
                                                </div>
                                            </div>                                                                                               
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="smtp_secure">SMTP Secure</label>
                                                    <select name="smtp_secure" id="smtp_secure" class="form-control">
                                                        <option value="ssl" <?php if($rowp['smtp_secure'] == 'ssl'){echo 'selected';} ?>>SSL</option>
                                                        <option value="tls" <?php if($rowp['smtp_secure'] == 'tls'){echo 'selected';} ?>>TLS</option>
                                                    </select>
                                                </div>
                                            </div>                                                                                               
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="from_email">From Email</label>
                                                    <input type="email" id="from_email" name="from_email" value="<?= $rowp['from_email']; ?>" class="form-control" placeholder="onlineexamsystem123@gmail.com" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="from_name">From Name</label>
                                                    <input type="text" id="from_name" name="from_name" value="<?= $rowp['from_name']; ?>" class="form-control" placeholder="Online Examination System" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="email_message">Email Message</label>
                                                    <textarea name="email_message" id="email_message" class="form-control" rows="10" style="white-space: pre-wrap;"><?= $rowp['email_message']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>                                                
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-wrapper">
                                        <div class="table-wrapper" style="max-width: 100%; height: 320px; padding:0px; margin: 0px; overflow-y: scroll; overflow-x:scroll; -webkit-overflow-scrolling: touch;">
                                            <table class="fixed-header table-striped table-bordered table-condensed" id="inputsTbl" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 7%;">
                                                            <button type="button" class="btn btn-info btn-xs" id="addNewRow"><i class="fa fa-plus"></i></button>
                                                        </th>
                                                        <th style="width: 55%;">CC Email</th>
                                                        <th style="width: 38%;">CC Email Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while($rowcc = $rescc->fetch_assoc()) : ?>
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-danger removeRow" data-id="<?= $rowcc['id']; ?>"><i class="fa fa-minus"></i></button>
                                                            <input type="hidden" name="cc_id[]" class="cc_id" value="<?= $rowcc['id']; ?>">
                                                        </td>                                                        
                                                        <td>
                                                            <input type="email" name="cc_email[]" value="<?= $rowcc['cc_email']; ?>" class="form-control cc_email" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="cc_email_name[]" value="<?php echo $rowcc['cc_name']; ?>" class="form-control cc_email_name" autocomplete="off">
                                                        </td>                                                                
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content wrapper -->
<?php define('CUSTOM_JS', 'js/email_setting.js'); ?>
<?php include_once 'inc/footer.php'; ?>