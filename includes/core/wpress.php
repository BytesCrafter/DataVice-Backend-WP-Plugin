<?php
	if ( ! defined( 'ABSPATH' ) ) 
	{
	        exit;
	}

	/** 
         * @package datavice-wp-plugin
         * @version 0.1.0
         * @author BytesCrafter
	 */

    add_action( 'show_user_profile', 'extra_user_profile_fields' );
    add_action( 'edit_user_profile', 'extra_user_profile_fields' );

    function extra_user_profile_fields( $user ) { ?>

        <h3><?php _e("Contact Reference (DataVice)", "blank"); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="Phone Number"><?php _e("Phone Number"); ?></label></th>
                <td>
                    <input type="text" name="datavice_phone" id="datavice_phone" value="<?php echo esc_attr( get_the_author_meta( 'datavice_phone', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your Phone."); ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="City Address"><?php _e("City Address"); ?></label></th>
                <td>
                    <input type="text" name="datavice_city" id="datavice_city" value="<?php echo esc_attr( get_the_author_meta( 'datavice_city', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your City Address."); ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="Country"><?php _e("Country"); ?></label></th>
                <td>
                    <select name="datavice_country" id="datavice_country">
                        <?php 
                            $dv_settings_country_list = DV_Countries::get_countries_raw(1); 
                            if($dv_settings_country_list['status'] == "success") {
                                ?>
                                    <option value="0">Select your Country </option>
                                <?php
                                foreach($dv_settings_country_list['data'] as $cur_country) { 
                                    ?>
                                        <option <?php if(esc_attr( get_the_author_meta( 'datavice_country', $user->ID ) ) == $cur_country->ID) { echo 'selected="selected"'; } ?>value="<?= $cur_country->ID ?>"><?= $cur_country->name ?></option>
                                    <?php
                                }
                            } else {
                                ?>
                                    <option value="0">No Active Countries Found</option>
                                <?php
                            }

                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="Zipcode"><?php _e("Zipcode"); ?></label></th>
                <td>
                    <input type="number" name="datavice_zipcode" id="datavice_zipcode" value="<?php echo esc_attr( get_the_author_meta( 'datavice_zipcode', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your Zipcode."); ?></span>
                </td>
            </tr>
        </table>

        <h3><?php _e("Social Media (DataVice)", "blank"); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="Facebook"><?php _e("Facebook"); ?></label></th>
                <td>
                    <input type="text" name="datavice_fbid" id="datavice_fbid" value="<?php echo esc_attr( get_the_author_meta( 'datavice_fbid', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your Facebook ID."); ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="Twitter"><?php _e("Twitter"); ?></label></th>
                <td>
                    <input type="text" name="datavice_twid" id="datavice_twid" value="<?php echo esc_attr( get_the_author_meta( 'datavice_twid', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your Twitter."); ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="Instagram"><?php _e("Instagram"); ?></label></th>
                <td>
                    <input type="text" name="datavice_igid" id="datavice_igid" value="<?php echo esc_attr( get_the_author_meta( 'datavice_igid', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your Instagram ID."); ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="YouTube"><?php _e("YouTube"); ?></label></th>
                <td>
                    <input type="text" name="datavice_ytid" id="datavice_ytid" value="<?php echo esc_attr( get_the_author_meta( 'datavice_ytid', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your YouTube Channel."); ?></span>
                </td>
            </tr>
        </table>

        <table class="form-table">
            <tr>
                <th><label for="Last Update"><?php _e("Last Update"); ?></label></th>
                <td>
                    <p><?php echo get_user_meta($user->ID, 'datavice_last', true) ?></p>
                </td>
            </tr>
        </table>
        
    <?php } 
    
        add_action( 'personal_options_update', 'datavice_save_extra_user_profile_fields' );
        add_action( 'edit_user_profile_update', 'datavice_save_extra_user_profile_fields' );

        function datavice_save_extra_user_profile_fields( $user_id ) {
            
            if ( !DV_Globals::verify_role_is('administrator') ) { 
                return false; 
            }
            update_user_meta( $user_id, 'datavice_phone', $_POST['datavice_phone'] );
            update_user_meta( $user_id, 'datavice_city', $_POST['datavice_city'] );
            update_user_meta( $user_id, 'datavice_country', $_POST['datavice_country'] );
            update_user_meta( $user_id, 'datavice_zipcode', $_POST['datavice_zipcode'] );

            update_user_meta( $user_id, 'datavice_fbid', $_POST['datavice_fbid'] );
            update_user_meta( $user_id, 'datavice_twid', $_POST['datavice_twid'] );
            update_user_meta( $user_id, 'datavice_igid', $_POST['datavice_igid'] );
            update_user_meta( $user_id, 'datavice_ytid', $_POST['datavice_ytid'] );

            update_user_meta( $user_id, 'datavice_last', date("Y-m-d h:i:s") );
        }

        add_filter('show_admin_bar', '__return_false');