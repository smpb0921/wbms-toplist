<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $source = isset($_GET['source'])?$_GET['source']:'';
    $ref = isset($_GET['txnnumber'])?$_GET['txnnumber']:'';
    $source = str_replace('tpicorpaabbcc', '&', $source);
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Print Preview [<?php echo $ref; ?>]

	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    		<div class="panel-body">
                <!-- CONTENT -->
                <iframe src="<?php echo $source; ?>" style='border:2px solid #black; height:77vh; width:100%'>


                </iframe>
                <!-- CONTENT-END -->
            </div>
        </div>
    </div>
</div>


