<?php
	include('config.php');
	
	if(isset($_POST['submit_client'])) {

		if(empty($_POST['id'])) {
			//Client Detail
			$client = new stdclass();
			$client->firstname 			= $_POST['clients']['firstname'];
			$client->middlename 		= $_POST['clients']['middlename'];
			$client->lastname 			= $_POST['clients']['lastname'];
			$client->birthdate 			= date( 'y-m-d', strtotime($_POST['clients']['birthdate']));
			$client->martialStatus 	    = $_POST['clients']['martialStatus'];
			$client->spouse 			= $_POST['clients']['spouse'];
			$client->permanentAddress   = $_POST['clients']['permanentAddress'];
			$client->currentAddress		= $_POST['clients']['currentAddress'];
			$client->contact 			= $_POST['clients']['contact'];
			$client->validID 			= $_POST['clients']['validID'];
			$client->validIDNo 			= $_POST['clients']['validIDNo'];
			$client->businessType 		= $_POST['clients']['businessType'];
			$client->businessName 		= $_POST['clients']['businessName'];
			$client->businessAddress 	= $_POST['clients']['businessAddress'];
			$client->yearsOperated		= $_POST['clients']['yearsOperated'];
			$client->dailySales 		= $_POST['clients']['dailySales'];
			$client->dailyExpenses 		= $_POST['clients']['dailyExpenses'];
			$client->applicationDate 	= date( 'y-m-d', strtotime($_POST['clients']['applicationDate']));
			$client->collateral 		= $_POST['clients']['collateral'];
			$client->remarks 			= $_POST['clients']['remarks'];
			
			if(!empty($_POST['clients']['active'])) { 
				$client->active = $_POST['clients']['active'];
			} else {
				$client->active = 0;
			}

			if(!empty($_POST['clients']['delinquent'])) { 
				$client->delinquent = $_POST['clients']['delinquent'];
			} else {
				$client->delinquent = 0;
			}
			
			$client->user_id 			= $_POST['clients']['user_id'];
			$client->position 			= $db->max("clients", "position") + 1;
			$client->created 			= time();
			$client->createdBy 			= $_SESSION['ID'];
		
			$client_id = $db->insertObject($client, "clients");
		
		
			$alert = new stdclass();
			if($client_id) {
				$alert->type = "success";
				$alert->msg  = "You have successfully added " . getClientName($client_id) . ".";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}	

			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;
	
		
			//Client image
			//Rename first to the client firstname and lastname
			$tempfilename = $_POST['images']['profilePicture'];
		
			if($tempfilename) {
				//Get the extension 
				$fileArr = explode("." , $tempfilename);
				$ext 	 = $fileArr[count($fileArr)-1];

				//Rename the temporary file name of the profile picture
				$newname = str_replace(" ", "_", $_POST['clients']['firstname'] . "_" . $_POST['clients']['lastname'] . "." . $ext);
				$old 	 = $configs['client_image_directory'] . $tempfilename;
				$new 	 = $configs['client_image_directory'] . $newname;
				rename($old , $new);
		
				//Add the image to database
				$image 				= new stdclass();
				$image->filename 	=  $newname;
				$image->type 		= "mainimage";
				$image->client_id 	= $client_id;
		
				$image_id = $db->insertObject($image, "images");
			}
			//Client references and comakers
			$ref_nums = count($_POST['references']['type']);
		
			if($ref_nums  > 0) {
			
				for($i = 0; $i < $ref_nums; $i++) {
					$reference 				= new stdclass();
					$reference->name 		=  $_POST['references']['name'][$i];
					$reference->address 	=  $_POST['references']['address'][$i];
					$reference->contact 	=  $_POST['references']['contact'][$i];
					$reference->type 		=  $_POST['references']['type'][$i];
					$reference->client_id 	=  $client_id;
					
					$reference_id = $db->insertObject($reference, "references");
				} 
			}
		
	
			//Client loan
			$loan = new stdclass();
			$loan->capital	    	= $_POST['loans']['capital'];
			$loan->interest     	= $_POST['loans']['interest'];
			$loan->client_id    	= $client_id;
			$loan->category_id  	= $_POST['loans']['category_id'];
			$loan->loanDate 		= date( 'y-m-d', strtotime($_POST['loans']['loanDate']));
			$loan->monthsToPay  	= $_POST['loans']['monthstopay'];
			$loan->paid     		= 0;
			$loan->remarks      	= $_POST['loans']['remarks'];
			$loan->created  		= time();
			$loan->createdBy  		= $_SESSION['ID'];
			$loan->active	    	= 1;
		
			$loan_id = $db->insertObject($loan, "loans");
		
			$alert = new stdclass();
		
			if($loan_id) {
				$alert->type = "success";
				$alert->msg  = "You have successfully added " . formatToAmount($loan->capital) . " loan for " . getClientName($loan->client_id) . ".";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}

			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;
		} else {
			//Client Detail
			$client_id = (int) $_POST['id'];
			$client = $db->selectObject("clients", "id={$client_id}");
			
			//Client image
			//Rename first to the client firstname and lastname
			$tempfilename = $_POST['images']['profilePicture'];
			$db->delete("images", "client_id = {$client_id}");
			if($tempfilename) {
				//Get the extension 
				$fileArr = explode("." , $tempfilename);
				$ext 	 = $fileArr[count($fileArr)-1];

				//Rename the temporary file name of the profile picture
				$newname =  str_replace(" ", "_", $_POST['clients']['firstname'] . "_" . $_POST['clients']['lastname'] . "." . $ext);
				$old 	 = $configs['client_image_directory'] . $tempfilename;
				$new 	 = $configs['client_image_directory'] . $newname;
				rename($old , $new);
		
				//Add the image to database
				
				$image 				= new stdclass();
				$image->filename 	=  $newname;
				$image->type 		= "mainimage";
				$image->client_id 	= $client_id;
		
				$image_id = $db->insertObject($image, "images");
			}
			
			foreach($_POST['clients'] as $key => $value) {
				$client->$key = $value; 
			}
			if(!empty($_POST['clients']['active'])) { 
				$client->active = $_POST['clients']['active'];
			} else {
				$client->active = 0;
			}

			if(!empty($_POST['clients']['delinquent'])) { 
				$client->delinquent = $_POST['clients']['delinquent'];
			} else {
				$client->delinquent = 0;
			}

			$res = $db->updateObject($client, "clients");
			
			$alert = new stdclass();
			if($res) {
				$alert->type = "success";
				$alert->msg  = "You have successfully updated " . getClientName($client_id) . " info.";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}	
			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;

		}
		
		//Redirect to the recently added client page
		header("location: client.php?id={$client_id}");
		exit();
		
	}
	
	
	
	$pageheader = "New Client";
	
	if(!empty($_GET['id'])) {
		$id = (int)$_GET['id'];
		
		$client 			= $db->selectObject("clients", "id = {$id}");
		$client->image  	= $db->selectValue("images", "filename", "client_id = {$client->id}");
		$client->comaker 	= $db->selectObjects("references", "client_id = {$client->id} AND type='comaker'");
		$client->reference  = $db->selectObjects("references", "client_id = {$client->id} AND type='reference'");
		
		$pageheader = "Edit Client";
	}
	
	$categories    = $db->selectObjects("categories");
	$martialStatus = getMartialStatus();
	$collectors    = $db->selectObjects("users", "user_type = 2 AND active = 1");

	include('header.php');
?>	

	<div class="row-fluid">
		<div class="span3">
		
			<div class="imageContainer">
	                <img alt=""  src="images/default.png" id="profileImage">
	                <a href="javascript:;" id="uploadFile" title="Upload">Upload Here</a>
	                <a href="javascript:;" id="delete" title="Delete" class="icon-trash" style="display:none;"></a>
	                <div id="messageBox"></div>
		    </div>
		</div>
		
		<div class="span9">
			<form action="addClient.php" method="post">
				<input type="hidden" name="id" value="<?php echo $client->id; ?>" />
				<input type="hidden" name="images[profilePicture]" id="profilePicture" value="<?php echo $client->image; ?>" />


			<fieldset>
				<legend>Personal Details</legend>
				<input type="text" placeholder="*&nbsp;First name" name="clients[firstname]" class="input-block-level" value="<?php echo @$client->firstname; ?>" required />
				<input type="text" placeholder="&nbsp;&nbsp;Middle name" name="clients[middlename]" class="input-block-level"  value="<?php echo @$client->middlename; ?>" />
				<input type="text" placeholder="* Last name" name="clients[lastname]" class="input-block-level" required  value="<?php echo @$client->lastname; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Birth Date" id="birthdate" name="clients[birthdate]" class="input-block-level" autocomplete="off" value="<?php echo @$client->birthdate; ?>" />
				
				<select name="clients[martialStatus]" id="martialStatus" class="input-block-level select_placeholder">
					<option value="" selected>&nbsp;&nbsp;Martial Status</option>
					<?php foreach($martialStatus as $status) : ?>
						<option value="<?php echo $status; ?>" <?php if($status == $client->martialStatus) echo "selected"; ?>><?php echo $status; ?></option>
					<?php endforeach; ?>
				</select>
				
				<input type="text" placeholder="&nbsp;&nbsp;Spouse" name="clients[spouse]" class="input-block-level" value="<?php echo @$client->spouse; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Permanent Address &nbsp;(Lot & blk no. street barangay, city, zipcode)" name="clients[permanentAddress]" class="input-block-level" value="<?php echo @$client->permanentAddress; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Current Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Lot & blk no. street barangay, city, zipcode)" name="clients[currentAddress]" class="input-block-level" value="<?php echo @$client->currentAddress; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Contact" name="clients[contact]" class="input-block-level" value="<?php echo @$client->contact; ?>" />
			</fieldset>
		    
			<fieldset>
				<legend>Business Information </legend>
				<input type="text" placeholder="&nbsp;&nbsp;Valid ID" name="clients[validID]" class="input-block-level"  value="<?php echo @$client->validID; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;ID No." name="clients[validIDNo]" class="input-block-level"  value="<?php echo @$client->validIDNo; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Type of Business" name="clients[businessType]" class="input-block-level"  value="<?php echo @$client->businessType; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Business Name" name="clients[businessName]" class="input-block-level"  value="<?php echo @$client->businessName; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Business Address&nbsp;(Lot & blk no. street barangay, city, zipcode)" name="clients[businessAddress]" class="input-block-level"  value="<?php echo @$client->businessAddress; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Years Operated" name="clients[yearsOperated]" class="input-block-level"  value="<?php echo @$client->yearsOperated; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Daily Sales" name="clients[dailySales]" class="input-block-level"  value="<?php echo @$client->dailySales; ?>" />
				<input type="text" placeholder="&nbsp;&nbsp;Daily Fixed Expense" name="clients[dailyExpenses]" class="input-block-level"  value="<?php echo @$client->dailyExpenses; ?>" />
			</fieldset>
		
			<fieldset>
				<legend>Other Details</legend>
				<input type="text" placeholder="&nbsp;&nbsp;Application Date" id="applicationDate" name="clients[applicationDate]" class="input-block-level" autocomplete="off" value="<?php echo $client->applicationDate; ?>" />
				
				<select name="clients[user_id]" id="user_id" class="input-block-level select_placeholder">
					<option value="" selected>&nbsp;&nbsp;Collector</option>
					<?php foreach($collectors as $item) : ?>
						<option value='<?php echo $item->id; ?>' <?php if($item->id == $client->user_id) echo "selected"; ?>><?php echo $item->firstname . ' ' . $item->lastname; ?></option>
					<?php endforeach; ?>
				</select>
				
				<input type="text" placeholder="&nbsp;&nbsp;Collateral" id="collateral" name="clients[collateral]" class="input-block-level" value="<?php echo $client->collateral; ?>" />
				<textarea name="clients[remarks]" placeholder="&nbsp;&nbsp;Client Remarks" id="client_remarks" cols="30" rows="10" class="input-block-level"><?php echo $client->remarks; ?></textarea>
					
					<div><input type="checkbox" name="clients[active]" id="active" <?php if($client->active) echo "checked"; ?> value="1" />Active?</div>
					<div><input type="checkbox" name="clients[delinquent]" id="delinquent" <?php if($client->delinquent) echo "checked"; ?> value="1" />Delinquent?</div>
			</fieldset>
			
				<?php if(empty($client->id)) : ?>
				<div class="referencecontainer"></div>
				<p>
					<i class="icon-plus-sign"></i><a href="javascript:;" class="addreference">Add Reference</a>  
					<i class="removereference icon-minus-sign"></i><a href="javascript:;" class="removereference">Remove Reference</a>
				</p>
				
					<div class="comakercontainer"></div>
					<p>
						<i class="icon-plus-sign"></i><a href="javascript:;" class="addcomaker">Add Co-maker</a>
						<i class="removecomaker icon-minus-sign"></i><a href="javascript:;" class="removecomaker">Remove Co-maker</a>

					</p>

				<fieldset>
					<legend>Loan Details</legend>
					<input placeholder="*&nbsp;Loan amount" type="text" name="loans[capital]" id="capital" class="input-block-level" required />

					<select name="loans[interest]" id="interest" class="input-block-level select_placeholder"  required>
						<option value="" selected>*&nbsp;Interest rate</option>
						<option value="0.0">0%</option>
<option value=".05">5%</option>
						<option value=".07">7%</option>
						<option value=".1">10%</option>
					</select>

					<select name="loans[monthstopay]" id="monthstopay" class="input-block-level select_placeholder"  required>
						<option value="" selected>*&nbsp;Number of months</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>

					<select name="loans[category_id]" id="category_id" class="input-block-level select_placeholder"  required>
						<option value="" selected>*&nbsp;Category</option>
						<?php
							foreach($categories as $item) {
								echo "<option value='{$item->id}'>{$item->category}</option>";
							}	
						?>
					</select>

					<input type="text" placeholder="*&nbsp;Date" name="loans[loanDate]" class="input-block-level loanDate" autocomplete="off"  required />
					<textarea name="loans[remarks]" placeholder="&nbsp;&nbsp;Loan Remarks" id="remarks" cols="30" rows="10" class="input-block-level"></textarea>
				</fieldset>

			
				<?php endif; ?>
			
			<button class="btn btn-primary" name="submit_client">Save Client</button>
			</form>
		</div>
	</div>

	<script type="text/javascript">
	
	$(function() {
		//Counters for the comaker and reference dynamic field
		var ctr_ref = 0;
		var ctr_co  = 0;
		//Hide the remove button for references and comaker
		$(".removereference").css("display" , "none");
		$(".removecomaker").css("display" , "none");
		
		//Set the application, birth and loan date to calendar jquery ui
		$("#birthdate").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: '1913:2013',
			dateFormat: 'yy-mm-dd'
		});
		$("#applicationDate").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$(".loanDate").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		
		//Make the select placeholder to be look like other placeholder color
		$(".select_placeholder").on("change", function () {
			if($(this).val() == "") $(this).addClass("empty");
			else $(this).removeClass("empty")
		});
		$(".select_placeholder").change();
		
		//Script for Dynamic adding of references
		$(".addreference").click(function() {
			
			ctr_ref++;
			if(ctr_ref > 1) {
				$(".reference_fieldset").removeClass("current");
			}
			var request = $.ajax({
			  url: "forms/reference.php",
			  type: "POST",
			  data: {counter : ctr_ref }
			}).done(function( data) {
	
				$(".referencecontainer").append(data);
				$(".removereference").css( "display", "inline-block" );
				
			});
			
		});
		
		//Removing of references   
		$("a.removereference").bind("click", function() {
			ctr_ref--;
			$(".reference_fieldset.current").remove();
			$(".referencecontainer .reference_fieldset:last-child").addClass("current");
			if(ctr_ref == 0) {
				$(".removereference").css("display" , "none");
			}
		});
		
		//Script for adding comaker
		$(".addcomaker").click(function() {
			ctr_co++;
			if(ctr_co > 1) {
				$(".comaker_fieldset").removeClass("current");
			}
			var request = $.ajax({
			  url: "forms/comaker.php",
			  type: "POST",
			  data: {counter : ctr_co }
			}).done(function( data) {
	
				$(".comakercontainer").append(data);
				$(".removecomaker").css( "display", "inline-block" );
				
			});
			
		});
		
		//Removing of comaker
		$("a.removecomaker").bind("click", function() {
			ctr_co--;
			$(".comaker_fieldset.current").remove();
			$(".comakercontainer .comaker_fieldset:last-child").addClass("current");
			if(ctr_co == 0) {
				$(".removecomaker").css("display" , "none");
			}
		});
		
		//Upload primary picture script 
		var uploadURL = "addImage.php";
        $('a#delete').click(function() {
            $('input#profileImageFile').val("");
            $('img#profileImage').attr("src","images/default.png");
            $('div#messageBox').html("Image deleted.");
            $('div#messageBox').attr("class","success");
            $('a#delete').hide();
			$('#profilePicture').val("");
        });

       $('a#uploadFile').file().choose(function(e, input) {
       		input.upload(uploadURL, function(res) {
			
			if (res=="invalid") {
            	$('div#messageBox').attr("class","error");
               	$('div#messageBox').html("Invalid extension!");
           	} else {
				$('#profilePicture').val(res);
                $('div#messageBox').attr("class","success");
                $('div#messageBox').html("File uploaded.");
                $('img#profileImage').attr("src","site/clients/"+res);
                $('input#profileImageFile').val(res);
                $('a#delete').show();
             	$(this).remove();
            }
        	}, '');                  
    	});

	    <?php if($client->image): ?>
			
		res = $('#profilePicture').val(),
			  
     	$('#profilePicture').val(res);
		$('div#messageBox').attr("class","success");
		$('img#profileImage').attr("src","site/clients/"+res);
		$('input#profileImageFile').val(res);
		$('a#delete').show();
		$('a#uploadFile').remove();

		<?php endif; ?>
	});
	</script>
	
	<?php include('footer.php'); ?>