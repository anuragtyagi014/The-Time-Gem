<?php
?>
<style>
    .time_gem_notice {
        margin-bottom: 50px;
        border: 3px solid #8224e3;
        padding: 10px;
        border-radius: 4px;
        background: #913fe6ab;
        color: #fff;
    }

    .timegem-pass-wrap {
        display: flex;
        margin-left: 14px;
    }

    .timegem-pass-wrap input.timegem-pass {
        padding: 6px 8px;
        width: 94px;
        border-color: #8224e3;
    }

    table.shop_table.shop_table_responsive.my_account_orders .timegem-pass-wrap button {
        padding: 6px;
        background: #8224e3;
        color: #fff;
        margin-left: -2px;
        border-radius: 0 6px 6px 0 !important;
        cursor: pointer;
    }

    .main-switch {
        display: flex;
    }

    .switch {
        display: inline-block;
        height: 34px;
        position: relative;
        width: 60px;
    }

    .switch input {
        display: none;
    }

    .slider {
        background-color: #ccc;
        bottom: 0;
        cursor: pointer;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        transition: .4s;
    }

    .slider:before {
        background-color: #fff;
        bottom: 4px;
        content: "";
        height: 26px;
        left: 4px;
        position: absolute;
        transition: .4s;
        width: 26px;
    }

    input:checked+.slider {
        background-color: #8224e3;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<?php
$show_message = false;
$user_id = get_current_user_id();
$args = array(
    'post_type' => 'time-gem',
    'author'        =>  $user_id,
    'orderby'       =>  'post_date',
    'order'         =>  'DESC',
    'posts_per_page' => -1,
    'post_status' => 'any'
);


$timegems = get_posts($args);
$tr = '';
if (!is_wp_error($timegems)) {
    if (count($timegems) > 0) {
        foreach ($timegems as $row) {
            if ($row->post_status == 'draft') {
                $show_message = true;
            }
            if ($row->post_status == 'publish') {
                $st = 'Published';
            } elseif ($row->post_status == 'disapprove') {
                $st = 'Disapproved';
            } else {
                $st = 'Pending Review';
            }

            //$st = 'Published';

            $pass = $row->post_password;

            $qr_url = get_stylesheet_directory_uri() . '/qr.php?link=' . get_the_permalink($row->ID);
            $viewButton = $st == 'Published' ? '<a style="margin-right:5px;" href="' . get_the_permalink($row->ID) . '" class="woocommerce-button button view">View</a>' : '';
            $tr .= '<tr>
                        <td>
                        ' . $row->post_title . '<br/><a href="' . $qr_url . '" target="_blank" style="color:#8224e3">QR Code</a>
                        </td>
                        <td>' . $st . '</td>
                        <td>
                            <b>Created:</b> ' . date("d/m/Y", strtotime($row->post_date)) . '
                            <p><b>Updated: </b>' . date("d/m/Y", strtotime($row->post_modified)) . '</p>
                        </td>
                        <td>
                            <div style="display:flex;width:100%;max-width:360px;">
                            <a style="margin-right:5px;" href="' . get_site_url() . '/update-my-time-gem/?selected=' . base64_encode($row->ID) . '&action=edit" class="woocommerce-button button view">Edit</a>
                                ' . $viewButton . '
                                <div class="main-switch" id="tid' . $row->ID . '">
                                    
                                    <label class="switch" for="' . $row->ID . '">
                                        <input type="checkbox" ' . ($pass ? 'checked' : '') . ' onclick="timegemShowPassField(this)" id="' . $row->ID . '"  />
                                        <div class="slider round"></div>
                                    </label>

                                    <div class="timegem-pass-wrap" style="' . ($pass ? '' : 'display:none;') . '" >
                                        <input type="text" name="timegem_Pass[]" class="timegem-pass" placeholder="Password" value="' . ($pass ? $pass : '') . '">
                                        <button type="button" onclick="timegemPass(' . $row->ID . ',this)"><span class="dashicons dashicons-saved"></span></button>
                                    </div>
                                </div>
                            </div>
                            <span>Toggle switch on to make profile private</span>
                        </td>
                    </tr>';
        }
    }
}

if ($show_message) {
    echo '<div class="time_gem_notice">
                <p>Thank you for providing the necessary information. We are currently in the process of creating your Time Gem profile while ensuring that all data adheres to our strict safety and privacy policies. Kindly allow up to 24 hours for your profile to be reviewed and approved. Your patience is greatly appreciated.</p>
            </div>';
}
?>

<h2 style="color:#8224e3;"><b><?php echo __('My Time Gem', 'woocommerce'); ?></b></h2>

<table class="shop_table shop_table_responsive my_account_orders">

    <thead>
        <tr>
            <th class=""><span class="nobr">Time Gem</span></th>
            <th class=""><span class="nobr">Status</span></th>
            <th class=""><span class="nobr">Date</span></th>
            <!--<th class=""><span class="nobr">Flip the switch to go from public to private</span></th>-->
            <th class=""><span class="nobr">Action</span></th>
        </tr>
    </thead>

    <tbody>
        <?php
        echo $tr;
        ?>
    </tbody>
</table>