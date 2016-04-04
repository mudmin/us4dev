
      <h3>User Information</h3>
  <div class="panel panel-default">
		 	 <div class="panel-heading">User ID: <?=$userdetails->id?></div>
			 <div class="panel-body">
				   <p>
						<label>Logins: </label>
						<?=$userdetails->logins?>
					  </p>
					  </p>
					  <p>
						<label>Username:
						  <input  class='form-control' type='text' name='username' value='<?=$userdetails->username?>' /></label>
						<label>Email:
						  <input class='form-control' type='text' name='email' value='<?=$userdetails->email?>' /></label>
						</p>
					  <p>
						<label>First Name:
						  <input  class='form-control' type='text' name='fname' value='<?=$userdetails->fname?>' /></label>
			
						<label>Last Name:
						  <input  class='form-control' type='text' name='lname' value='<?=$userdetails->lname?>' /></label>
						</p>

						<!-- Will be implemented in a later release -->
						<!-- <label> Account Active?:
							<input type="radio" name="active" value="1" <?php echo ($userdetails->active==1)?'checked':''; ?> size="25">Yes</input></label>
							<input type="radio" name="active" value="0" <?php echo ($userdetails->active==0)?'checked':''; ?> size="25">No</input></label> -->
						  <p>
			 </div>
          </div>	
		 	  


