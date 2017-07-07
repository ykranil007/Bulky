<!doctype html>

<html>

<!--=============Top Header=============-->

<?php $this->load->view('shared/viw_header');?>

<!--==============end header=================-->

<body>

<!--=============Top navigaion bar =============-->

<?php $this->load->view('shared/viw_navigaion_bar');?>

<!--==============end navigaion bar=================-->

<!--=============Top Side bar =============-->

<?php $this->load->view('dashboard/view_userAccount_sidebar');?>

<!--==============end Side bar=================-->

        <div class="col_content personal_info">

            <h3>Personal Information</h3>

            <form  action=""method="" id="userPersonalInfo" class="form-horizontal">

                <div class="form-group">

                    <label>First Name</label>

                    <div class="col-value"><input type="text" id="myFirstName" name="myFirstName" value="<?php echo $user_info->first_name;?>">
					<span id="user_error_info" style="display:none; color:red; "></span>
                    
                    </div>


                </div>

                <div class="form-group">

                    <label>Last Name</label>

                    <div class="col-value"><input type="text" id="myLastName" name="myLastName" value="<?php echo $user_info->last_name;?>"></div>

                </div>

                <div class="form-group">

                    <label>Gender</label>

                    <div class="col-value">

                    <select name=" myGender" id="my_gender" >

                        <option <?php if($user_info->gender == 'Male') echo 'selected'; ?> >Male</option>

                        <option <?php if($user_info->gender == 'Female') echo 'selected'; ?> >Female</option>

                    </select>

                    </div>

                </div>

                <div class="col-offset"><button id="userUpdateButton" type="button">Save Changes</button></div>

                <br>

                <div id="SuccessMsg" class="user-pinfo-update-msg"></div>

            </form>

        </div>     

</section>

<!--=============footer Start=============-->

<?php $this->load->view('shared/viw_footer');?>

<!--==============end footer=================-->

</body>

<!--=============footer Start=============-->

<?php $this->load->view('shared/viw_links');?>

<!--==============end footer=================-->

</html>

