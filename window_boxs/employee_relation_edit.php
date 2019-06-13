<?php include_once "wb_head.php" ;?>
<body>	
	<div class="modal-body" id="Add">
		<?php
			$employee_relations	= $db->fetch_all_data("employee_relations",[],"id = '".$_GET["data_id"]."'")[0];
			$employee_id	= $_GET["employee_id"];
			$employee_name = $db->fetch_single_data("employees","name",array("id"=>$employee_id));

			if(isset($_POST["saving_new"])){
				$db->addtable("employee_relations");	$db->where("id",$_GET["data_id"]);
				$db->addfield("employee_id");		$db->addvalue($employee_id);
				$db->addfield("relation_id");		$db->addvalue($_POST["relation_id"]);
				$db->addfield("fullname");			$db->addvalue($_POST["fullname"]);
				$db->addfield("division");			$db->addvalue($_POST["division"]);
				$db->addfield("location");			$db->addvalue($_POST["location"]);
				$inserting = $db->update();
				if($inserting["affected_rows"] > 0){
					$_SESSION["alert_success"] = "Data saved successfully!";
					?><script type="text/JavaScript">setTimeout("location.href = '<?=str_replace("_edit","_list",$_SERVER["PHP_SELF"]);?>?employee_id=<?=$employee_id;?>';",1500);</script><?php
				} else {
					$_SESSION["alert_danger"] = "Failed to saved!";
				}	
			}
		?>

		<div class="login mx-auto mw-100">
			<h5 class="text-center">Employee Relation - Edit</h5>
				
				<!--form -->
				<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
					<div class="container">
						<?php include_once "../a_notification.php"; ?>
						<div class="info-para">
							<?=$f->start("","POST","","enctype='multipart/form-data'");?>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Employee</font>
										<?=$f->input("",$employee_name,"readonly","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Relation</font>
										<?=$f->select("relation_id",$db->fetch_select_data("relations","id","name",[],[],"",true),@$employee_relations["relation_id"],"","select_form");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Full Name</font>
										<?=$f->input("fullname",@$employee_relations["fullname"],"required","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Division</font>
										<?=$f->input("division",@$employee_relations["division"],"t","form-control");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Location</font>
										<?=$f->input("location",@$employee_relations["location"],"","form-control");?>
									</div>
								</div>
								<div class="text-left click-subscribe">
									<?=$f->input("saving_new","Save","type='submit'","btn btn-primary");?>
									<?=$f->input("cancel","Cancel","type='button' onclick=\"window.location='".str_replace("_edit","_list",$_SERVER["PHP_SELF"])."?employee_id=".$employee_id."';\"","btn btn-secondary");?>
								</div>
							<?=$f->end();?>
							
						</div>
					</div>
				</section>
				<!--//form  -->
				
		</div>
	</div>
</body>	


