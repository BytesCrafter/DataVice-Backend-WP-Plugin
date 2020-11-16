
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

    /**
	 * @package datavice-wp-plugin
     * @version 0.1.0
    */

?>

<form id="activation-form" action="" method="POST">

    <div class="form-row">

        <div class="col-md-12 mb-12">
            <label for="ak">Activation Key</label>
            <input type="ak" class="form-control" name="ak" id="ak" placeholder="Activation Key" required>
        </div>

    </div>

    <div class="form-row">

        <div class="col-md-6 mb-12">
            <label for="npas">New Password</label>
            <input type="password" class="form-control" name="npas" id="npas" placeholder="New Password" required>
        </div>

        <div class="col-md-6 mb-12">
            <label for="cpas">Confirm Password</label>
            <input type="password" class="form-control" name="cpas" id="cpas" placeholder="Confirm Password" required>
        </div>

    </div>

    <div class="form-row" style="width: 360px; margin: auto; margin-top: 25px; ">
        <button type="submit" id="submit-btn" class="btn btn-primary btn-block"> Activate Account </button>
    </div> <!-- form-group// -->

</form>

<script type="text/javascript">
    jQuery(document).ready( function ( $ ) 
    {    
        //Serialize form data.
        function getFormData($form){
            var indexed_array = {}

            indexed_array['ak']  = $('#ak').val()
            indexed_array['npas'] = $('#npas').val()
            indexed_array['cpas']  = $('#cpas').val()

            return indexed_array;
        }

        $("#activation-form").submit(function (e) {
            e.preventDefault();

            // setup some local variables
            var $form = $(this);

            // Let's select and cache all the fields
            var $inputs = $form.find("input, select, button, textarea");

            // Let's disable the inputs for the duration of the Ajax request.
            // Note: we disable elements AFTER the form data has been serialized.
            // Disabled form elements will not be serialized.
            $inputs.prop("disabled", true);

            // Serialize the data in the form
            var formDataActivate = getFormData();

            $.ajax({
                dataType: 'json',
                type: 'POST', 
                data: formDataActivate,
                url:'<?= site_url() ?>/wp-json/datavice/v1/user/activate/verify',
                success:function(response){
                    response.status!="success"? alert(response.message) : alert("You're account is now activated! You can now login.");
                    if(response.status == "success") {
                        $('#activation-form').trigger("reset");
                    }
                    $inputs.prop("disabled", false);
                },
                error : function(jqXHR, textStatus, errorThrown) 
                {
                    console.log("" + JSON.stringify(jqXHR) + " :: " + textStatus + " :: " + errorThrown);
                    $inputs.prop("disabled", false);
                }
            });
        });
    });
</script>