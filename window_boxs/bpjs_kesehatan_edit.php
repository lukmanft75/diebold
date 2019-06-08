<?php include_once "wb_head.php" ;?>
<body>	
	<div class="modal-body" id="Edit">
		<?php
			$bpjs_id		= $_GET["bpjs_id"];
			$bpjs_details	= $db->fetch_all_data("bpjs",[],"id = '".$bpjs_id."'")[0];
			if($_GET["candidate_id"] > 0) {
				$candidate_id	= $_GET["candidate_id"];
				$candidate_name = $db->fetch_single_data("candidates","name",array("id"=>$candidate_id));
			}
			if($_GET["employee_id"] > 0) {
				$employee_id	= $_GET["employee_id"];
				$employee_name	= $db->fetch_single_data("employees","name",array("id"=>$employee_id));
			}

			if(isset($_POST["saving_new"])){
				$db->addtable("bpjs");				$db->where("id",$bpjs_id);
				$db->addfield("bpjs_type");			$db->addvalue("1");
				$db->addfield("name");				$db->addvalue($_POST["name"]);
				$db->addfield("birthdate");			$db->addvalue($_POST["birthdate"]);
				$db->addfield("sex");				$db->addvalue($_POST["sex"]);
				$db->addfield("status_id");			$db->addvalue($_POST["status_id"]);
				$db->addfield("pisa");				$db->addvalue($_POST["pisa"]);
				$db->addfield("pkwt_from");			$db->addvalue($_POST["pkwt_from"]);
				$db->addfield("ktp");				$db->addvalue($_POST["ktp"]);
				$db->addfield("bpjs_id");			$db->addvalue($_POST["bpjs_id"]);
				$db->addfield("email");				$db->addvalue($_POST["email"]);
				$db->addfield("remarks");			$db->addvalue($_POST["remarks"]);
				$db->addfield("info_to_empl_at");	$db->addvalue($_POST["info_to_empl_at"]);
				$inserting = $db->update();			
				if($inserting["affected_rows"] > 0){
					foreach($_FILES as $file_mode => $arrfiles){
						if($arrfiles["tmp_name"]){
							$_ext = strtolower(pathinfo($_FILES[$file_mode]['name'],PATHINFO_EXTENSION));
							if($file_mode == "softcopy") $softcopy_name = $candidate_id."_bpjs_".$bpjs_id.".".$_ext;
							if($file_mode == "file_ktp") $softcopy_name = $candidate_id."_ktp_".$bpjs_id.".".$_ext;
							if($file_mode == "file_kk") $softcopy_name = $candidate_id."_kk_".$bpjs_id.".".$_ext;
							if($file_mode == "file_pernyataan") $softcopy_name = $candidate_id."_pernyataan_".$bpjs_id.".".$_ext;
							if($file_mode == "file_kjpensiun") $softcopy_name = $candidate_id."_kjpensiun_".$bpjs_id.".".$_ext;
							move_uploaded_file($arrfiles['tmp_name'],"../files_bpjs/".$softcopy_name);
							$db->addtable("bpjs");			$db->where("id",$bpjs_id);
							if($file_mode == "softcopy") $db->addfield("softcopy");
							if($file_mode == "file_ktp") $db->addfield("file_ktp");
							if($file_mode == "file_kk") $db->addfield("file_kk");
							if($file_mode == "file_pernyataan") $db->addfield("file_pernyataan");
							if($file_mode == "file_kjpensiun") $db->addfield("file_kjpensiun");
							$db->addvalue($softcopy_name);
							$db->update();
						}
					}
					$_SESSION["alert_success"] = "Data saved successfully!";
					?><script type="text/JavaScript">setTimeout("location.href = '<?=str_replace("_edit","_list",$_SERVER["PHP_SELF"]);?>?candidate_id=<?=$candidate_id;?>&employee_id=<?=$employee_id;?>';",1500);</script><?php
				} else {
					$_SESSION["alert_danger"] = "Failed to saved!";
				}	
			}
		?>

		<div class="login mx-auto mw-100">
			<h5 class="text-center">BPJS Kesehatan - Edit</h5>
				
				<!--form -->
				<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
					<div class="container">
						<?php include_once "../a_notification.php"; ?>
						<div class="info-para">
							<?=$f->start("","POST","","enctype='multipart/form-data'");?>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<?php if($candidate_id > 0){ ?>
											<font style="color:#1a75ff;font-style:italic;">Candidate</font>
											<?=$f->input("",$candidate_name,"readonly","form-control");?>
										<?php } else if($employee_id > 0) { ?>
											<font style="color:#1a75ff;font-style:italic;">Employee</font>
											<?=$f->input("",$employee_name,"readonly","form-control");?>
										<?php }?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Name</font>
										<?=$f->input("name",@$bpjs_details["name"],"","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Birthdate</font>
										<?=$f->input("birthdate",@$bpjs_details["birthdate"]," type='date' style='height:43px;'","form-control");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Sex</font>
										<?=$f->select("sex",[""=>"","M"=>"M","F"=>"F"],@$bpjs_details["sex"],"required","select_form");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Marital Status</font>
										<?=$f->select("status_id",$db->fetch_select_data("statuses","id","name",[],[],"",true),@$bpjs_details["status_id"],"required","select_form");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">PISA</font>
										<?=$f->select("pisa",["peserta" => "Peserta", "istri" => "Istri", "suami"=>"Suami", "anak" => "Anak"],@$bpjs_details["pisa"],"required","select_form");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">PKWT From</font>
										<?=$f->input("pkwt_from",@$bpjs_details["pkwt_from"]," type='date' style='height:43px;'","form-control");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">NIK</font>
										<?=$f->input("ktp",@$bpjs_details["ktp"]," type='number' required","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">No BPJS</font>
										<?=$f->input("bpjs_id",@$bpjs_details["bpjs_id"],"type='number'","form-control");?>
									</div>
								</div>
								<div class="form-group contact-forms">
									<font style="color:#1a75ff;font-style:italic;">Remarks</font>
									<?=$f->textarea("remarks",@$bpjs_details["remarks"]," rows='3'","form-control");?>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Info To Employer At</font>
										<?=$f->input("info_to_empl_at",@$bpjs_details["info_to_empl_at"]," type='date' style='height:43px;'","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Email</font>
										<?=$f->input("email",@$bpjs_details["email"]," type='email'","form-control");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Softcopy BPJS
										<?php if(@$bpjs_details["softcopy"] != "") echo " <a href=\"../files_bpjs/".@$bpjs_details["softcopy"]."\" target=\"_BLANK\" style='color:blue;'>- ".$bpjs_details["softcopy"]."</a>";?>
										</font>
										<?=$f->input("softcopy","","type='file'","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Softcopy KTP
										<?php if(@$bpjs_details["file_ktp"] != "") echo " <a href=\"../files_bpjs/".@$bpjs_details["file_ktp"]."\" target=\"_BLANK\" style='color:blue;'>- ".$bpjs_details["file_ktp"]."</a>";?>
										</font>
										<?=$f->input("file_ktp","","type='file'","form-control");?>
									</div>
								</div>
								<div class="row wls-contact-mid">
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Softcopy KK
										<?php if(@$bpjs_details["file_kk"] != "") echo " <a href=\"../files_bpjs/".@$bpjs_details["file_kk"]."\" target=\"_BLANK\" style='color:blue;'>- ".$bpjs_details["file_kk"]."</a>";?>
										</font>
										<?=$f->input("file_kk","","type='file'","form-control");?>
									</div>
									<div class="col-md-6 col-sm-6 form-group contact-forms">
										<font style="color:#1a75ff;font-style:italic;">Surat Pernyataan
										<?php if(@$bpjs_details["file_pernyataan"] != "") echo " <a href=\"../files_bpjs/".@$bpjs_details["file_pernyataan"]."\" target=\"_BLANK\" style='color:blue;'>- ".$bpjs_details["file_pernyataan"]."</a>";?>
										</font>
										<?=$f->input("file_pernyataan","","type='file'","form-control");?>
									</div>
								</div>
								<div class="text-left click-subscribe">
									<?=$f->input("saving_new","Save","type='submit'","btn btn-primary");?>
									<?=$f->input("cancel","Cancel","type='button' onclick=\"window.location='".str_replace("_edit","_list",$_SERVER["PHP_SELF"])."?candidate_id=".$candidate_id."&employee_id=".$employee_id."';\"","btn btn-secondary");?>
								</div>
							<?=$f->end();?>
							
						</div>
					</div>
				</section>
				<!--//form  -->
				
		</div>
	</div>
</body>	


