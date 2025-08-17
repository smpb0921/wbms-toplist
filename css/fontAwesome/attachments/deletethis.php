<html>
<head>
	<title></title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<style type="text/css">
			.padded-with-border{
				padding: 10px;
				border:1px solid #ddd;
				border-top: 1px solid #fff;
				border-left: 1px solid #fff;
				overflow: hidden;
				background: -webkit-linear-gradient(#F9FBFB, #E7EDF1);
			    background: -o-linear-gradient(#F9FBFB, #E7EDF1);
			    background: -moz-linear-gradient(#F9FBFB, #E7EDF1);
			    background: linear-gradient(#F9FBFB, #E7EDF1);
			}
			.section-trans-wrapper {
			  display: -webkit-flex;
			  display: flex;
			  -webkit-flex-direction: row; /* Safari */
  			  flex-direction:row;
  			  -webkit-flex-wrap: wrap; /* Safari */
  			  flex-wrap:   wrap;
			}
			.section-trans-1, .section-trans-2{
				background: -webkit-linear-gradient(#F9FBFB, #E7EDF1);
			    background: -o-linear-gradient(#F9FBFB, #E7EDF1);
			    background: -moz-linear-gradient(#F9FBFB, #E7EDF1);
			    background: linear-gradient(#F9FBFB, #E7EDF1);
				padding: 20px;
			}
			.section-trans-1{
			    width: 20%;
			}
			.section-trans-2{
				width: 80%;
			}
			@media(max-width: 1170px){
				.section-trans-1{
			          width: 25%;
				}
				.section-trans-2{
					width: 75%;
				}
			}
			@media(max-width: 992px){
				.section-trans-1{
			          width: 30%;
				}
				.section-trans-2{
					width: 70%;
				}
			}
			@media(max-width: 768px){
				.section-trans-1{
			          width: 100%;
				}
				.section-trans-2{
					width: 100%;
				}
			}
	</style>
</head>
<body>
	<div class='section-trans-wrapper'>
		<div class='section-trans-1'>
			<div class='col-md-12'>
				<div class="padded-with-border">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                                <label class='control-label col-lg-4'>SO No.</label>
                                                <div class="col-lg-8">
                                                    <input type='text' name='code' class='form-input form-control so-transnumber'>
                                                </div>
                                        </div>
                                    </form>
                                    <div class="btn-group btn-group-justified btn-group-sm margin-bottom-xs" role="group" aria-label="...">
                                      <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
                                        </button>
                                      </div>
                                      <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                        </button>
                                      </div>
                                      <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
                                        </button>
                                      </div>
                                      <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
                                        </button>
                                      </div>
                                    </div>
                                    <div class='transaction-status-div margin-bottom-xs so-status'>NEW</div>
                                    <div class='button-group-btn fluid' id='so-searchbtn'>
                                        <i class='fa fa-search fa-lg fa-space'></i>Search
                                    </div>
                               
                </div>
            </div>
		</div>
		<div class='section-trans-2'>
			<div class="padded-with-border">
							<div class='col-md-12 no-padding-left no-padding-right'>
                                    <form class="form-horizontal">
                                        <div class="col-lg-7">
                                            
                                            <div class="form-group">
                                                <label class='control-label col-md-3'>Customer</label>
                                                <div class="col-md-9">
                                                    <select class='form-control form-input so-customer'>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class='control-label col-md-3'>Reference</label>
                                                <div class="col-md-6">
                                                    <input type='text' class='form-input form-control so-reference'>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class='control-label col-md-3'>Remarks</label>
                                                <div class="col-md-9">
                                                    <textarea class='form-control so-remarks' rows='3'></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class='control-label col-md-3'>Approved by</label>
                                                <div class="col-md-6">
                                                    <input type='text' class='form-input form-control so-approvedby'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            
                                            
                                            <div class="form-group">
                                                <label class='control-label col-lg-4'>Date Reserved</label>
                                                <div class="col-lg-8">
                                                    <input type='text' class='form-input form-control so-datereserved'>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class='control-label col-lg-4'>Updated</label>
                                                <div class="col-lg-8">
                                                    <input type='text' class='form-input form-control so-dateupdated'>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class='control-label col-lg-4'>Updated by</label>
                                                <div class="col-lg-8">
                                                    <input type='text' class='form-input form-control so-updatedby'>
                                                </div>
                                            </div>
                                            <div class="form-group"><br></div>
                                            <div class="form-group">
                                                <label class='control-label col-lg-4'>Grand Total</label>
                                                <div class="col-lg-8">
                                                    <input type='text' class='form-input form-control so-grandtotal'>
                                                </div>
                                            </div>


                                        </div>
                                        
                                    </form>
                            </div>
            </div>
		</div>
	</div>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/jquery-1.11.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>