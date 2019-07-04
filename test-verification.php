<?php
include("dbs.php");
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: login.php');
}

$error = array();
$sql = "SELECT * FROM users WHERE id=" . $_SESSION['id'];
$result = $conn->query($sql);
$api_key = "";
$api_url = "https://ridb.recreation.gov/api/v1";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $api_key = $row['api_access_key'];
} else {
    $error = array('status' => 0, 'message' => 'Please setup an API Access Key.');
}
$fetch_response = "";
if (isset($_POST['api_url']) && !empty($_POST['api_url'])) {
    $params = "?apikey=" . $api_key;
    if (isset($_POST['api_data_param']) && !empty($_POST['api_data_param'])) {
        $params .= $_POST['api_data_param'];
    }

    $fetch_response = file_get_contents($api_url . $_POST['api_url'] . $params);
//    $fetch_response = json_decode($fetch_response); 
}
include 'header.php';
?>

<div class="container">
    <div class="col-md-8">
        <h3 class="text-center">API Verification Test</h3>
        <form class="form-horizontal" action="" method="post" id="api-form">
            <?php
            if (count($error) > 0 && $error['status'] == 1) {
                ?>
                <div class="alert alert-success"><?php echo $error['message']; ?></div>
                <?php
            }
            ?>
            <?php
            if (count($error) > 0 && $error['status'] == 0) {
                ?>
                <div class="alert alert-danger"><?php echo $error['message']; ?></div>
                <?php
            }
            ?>
            <div class="alert alert-danger js-errors"></div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $api_url; ?></span>
                    <input id="api_url" type="text" class="form-control" name="api_url" placeholder="Enter API URL e.g /activities">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Your API Key</span>
                    <input id="api_key" type="text" disabled class="form-control" value="<?php echo $api_key; ?>" name="api_key" placeholder="Enter API KEY e.g 3811a783-38cd-4b5c-84a0-ed5dda2013d8">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Data Params</span>
                    <input id="api_data_param" type="text" class="form-control" name="api_data_param" placeholder="Enter query string data (Optional) e.g &test=1&var1=2&var3=786">
                </div>
            </div>

            <button type = "button" onclick="RunCall();" class = "btn btn-default">Run Call</button>
        </form>
        <br/><br/>
        <div class="panel panel-default">
            <div class="panel-heading btn-default">Response:</div>
            <div class="panel-body"><code class="response-output"><?php echo $fetch_response; ?></code></div>
        </div>
    </div>
    <div class="col-md-4">
        <h3 class="text-center">RIDB API LIST</h3>
        <div class="panel panel-default">
            <div class="panel-heading btn-default">Click API:</div>
            <div class="panel-body">
                <h4>Activities</h4>
                <ul>
                    <li><code>/activities</code></li>
                    <li><code>/activities/{activityId}</code></li>
                    <li><code>/recareas/{recAreaId}/activities</code></li>
                    <li><code>/recareas/{recAreaId}/activities/{activityId}</code></li>
                    <li><code>/facilities/{facilityId}/activities</code></li>
                    <li><code>/facilities/{facilityId}/activities/{activityId}</code></li>
                </ul>
                
                <h4>Permit Entrances</h4>
                <ul>
                    <li><code>/permitentrances</code></li>
                    <li><code>/permitentrances/{permitentranceId}</code></li>
                    <li><code>/facilities/{facilityId}/permitentrances</code></li>
                    <li><code>/facilities/{facilityId}/permitentrances/{permitEntranceId}</code></li>
                </ul>
                <a target="_blank" href="https://ridb.recreation.gov/docs" class="btn btn-default pull-right">Read More</a>
            </div>
        </div>
    </div>
</div>

<script>
    function RunCall()
    {
        var api_url = $("#api_url").val();
        var api_key = $("#api_key").val();
        var api_data_param = $("#api_data_param").val();

        if (api_url == "" || api_key == "")
        {
            $(".response-output").html('');
            $(".js-errors").text("Please provide API URL & API Key. Both are required!");
            $(".js-errors").fadeIn(function () {
                setTimeout(function () {
                    $(".js-errors").fadeOut();
                    $(".js-errors").text("");
                }, 3000);
            });
        } else {
            $("#api-form").submit();
        }
    }

</script>
</body>
</html>
