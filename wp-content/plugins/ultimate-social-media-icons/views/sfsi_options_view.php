<!-- Loader Image section  -->
<div id="sfpageLoad">

</div>
<!-- END Loader Image section  -->

<!-- javascript error loader  -->
<div class="error" id="sfsi_onload_errors" style="margin-left: 60px;display: none;">
    <p>We found errors in your javascript which may cause the plugin to not work properly. Please fix the error:</p>
    <p id="sfsi_jerrors"></p>
</div> <!-- END javascript error loader  -->

<!-- START Admin view for plugin-->
<div class="wapper sfsi_mainContainer">

    <!-- Get notification bar-->
    <?php if (get_option("show_new_notification") == "yes") { ?>
        <script type="text/javascript">
            jQuery(document).ready(function(e) {
                jQuery(".sfsi_show_notification").click(function() {
                    SFSI.ajax({
                        url: sfsi_icon_ajax_object.ajax_url,
                        type: "post",
                        data: {
                            action: "notification_read",
                            nonce: "<?php echo wp_create_nonce('notification_read'); ?>"
                        },
                        success: function(msg) {
                            if (jQuery.trim(msg) == 'success') {
                                jQuery(".sfsi_show_notification").hide("fast");
                            }
                        }
                    });
                });
            });
        </script>
        <style type="text/css">
            .sfsi_show_notification {
                float: left;
                margin-bottom: 45px;
                padding: 12px 13px;
                width: 98%;
                background-image: url(<?php echo SFSI_PLUGURL; ?>images/notification-close.png);
                background-position: right 20px center;
                background-repeat: no-repeat;
                cursor: pointer;
                text-align: center;
            }
        </style>
        <!-- <div class="sfsi_show_notification" style="background-color: #38B54A; color: #fff; font-size: 18px;">
        New: You can now also show a subscription form on your site, increasing sign-ups! (Question 8)
        <p>
        (If question 8 gets displayed in a funny way then please reload the page by pressing Control+F5(PC) or Command+R(Mac))
        </p>
    </div> -->
    <?php } ?>
    <!-- Get notification bar-->
    <div class="sfsi_notificationBannner"></div>

    <!-- Get new_notification bar-->
    <script type="text/javascript">
        jQuery(document).ready(function() {

            jQuery("#floating").click(function() {
                jQuery("#ui-id-9").trigger("click");
                jQuery('html, body').animate({
                    scrollTop: jQuery("#ui-id-9").offset().top - jQuery("#ui-id-9").height()
                }, 2000);
            });

            jQuery("#afterposts").click(function() {
                if ("none" == jQuery("#ui-id-12").css('display')) {
                    jQuery("#ui-id-11").trigger("click");
                }
                jQuery('html, body').animate({
                    scrollTop: jQuery("#ui-id-11").offset().top - jQuery("#ui-id-11").height()
                }, 2000);
            });

        });
    </script>

    <?php
    $sfsi_install_date_time = date('Y-m-d H:i:s');
    $sfsi_install_time = strtotime(get_option('sfsi_installDate'));
    $sfsi_max_show_time = $sfsi_install_time + (120 * 60);
    $sfsi_current_date = strtotime(date('Y-m-d h:i:s'));
    $sfsi_intro_time_in_minutes = ceil(($sfsi_max_show_time - strtotime(date('Y-m-d h:i:s'))) / 60);
    $sfsi_intro_time_in_minutes = $sfsi_intro_time_in_minutes - 1;
    // $sfsi_intro_hours  = floor($sfsi_intro_time_in_minutes / 60);
    // $sfsi_intro_minutes  = $sfsi_intro_time_in_minutes - floor($sfsi_intro_time_in_minutes / 60) * 60;
    // $sfsi_intro_seconds  = ((($sfsi_intro_time_in_minutes) * 60) % 60);
    // var_dump($checked ,floor((($checked % ( 60 * 60)) / ( 60))));
    // var_dump((floor((($checked % ( 60 * 60 * 24)) / ( 60 * 60))/12)));
    $sfsi_intro_year = substr($sfsi_install_date_time, 0, 4);
    $sfsi_intro_month =  substr($sfsi_install_date_time, 5, 2) - 1;
    $sfsi_intro_day =  substr($sfsi_install_date_time, 8, 2);
    $sfsi_intro_hours = substr($sfsi_install_date_time, 11, 2);
    $sfsi_intro_minutes = substr($sfsi_install_date_time, 14, 2);
    $sfsi_intro_seconds = substr($sfsi_install_date_time, 17, 2);

    ?>
    <!-- Top content area of plugin -->
    <div class="main_contant">
        <?php
        $sfsi_new_intro_banner_hide_option = unserialize(get_option('sfsi_new_intro_banner_hide_option'));
        ?>
        <div class="sfsi_new_intro pb-4" style="<?php echo ($sfsi_new_intro_banner_hide_option['sfsi_display_section'] == "true") ?  'display:block !important;' : 'display:none!important;'; ?>">
            <div class="row justify-content-center">
                <div>
                    <div class="px-2 py-5 sfsi_intro_bg_white sfsi_intro_section">
                        <div class="text-center">
                            <h1 style="color:#8A4983;font-family: montserrat-regular; "><b>Welcome to the </b><b style="font-family: montserrat-extrabold ">Ultimate Social Media Plugin!</b></h1>
                        </div>
                        <div class="row py-3">
                            <div class="col-12 col-md-2 col-lg-2 col-xxl-2 d-lg-flex justify-content-lg-end align-items-center pr-lg-0">
                                <img src="<?php echo SFSI_PLUGURL . "images/new_intro_icons/get_started.png"; ?>" class="sfsi_intro_images" alt="get started" />
                            </div>
                            <div class="col-12 col-md-10 col-lg-10 col-xxl-10 py-lg-2 sfsi_intro_text_section px-sm-4 pl-lg-0">
                                <div class="font-weight-bold sfsi_intro_sub_heading">Get started:</div>
                                <div class="sfsi_intro_sub_text">Simply answer the questions below (at least the first 3) - that's it!</div>
                            </div>
                        </div>
                        <?php
                        if (
                            $sfsi_max_show_time > strtotime(date('Y-m-d h:i:s'))
                        ) {
                            ?>
                            <div class="sfsi_intro_special_discount row py-3">
                                <div class="col-12 col-md-2 col-lg-2 col-xxl-2 d-lg-flex justify-content-lg-end align-items-center">
                                    <img src="<?php echo SFSI_PLUGURL . "images/new_intro_icons/discount.png"; ?>" class="sfsi_intro_images" alt="discount" />
                                </div>
                                <div class="col-12 col-md-10 col-lg-10 col-xxl-10 py-lg-2 sfsi_intro_text_section px-sm-4 pl-lg-0 pr-xl-0 pr-xl-3">
                                    <div class="font-weight-bold sfsi_intro_sub_heading">Special discount:</div>
                                    <div class="d-lg-flex justify-content-lg-between align-items-lg-start justify-content-xl-between">
                                        <div class="sfsi_intro_sub_text pb-sm-3">
                                            <div>For newbies we offer a cool <strong class="font-weight-bold sfsi_into_text_bold" style="font-size: 20px;color:black;">30% discount</strong></div>
                                            <div>for the premium plugin which will expire in....</div>
                                        </div>
                                        <div class="d-flex sfsi_into_time_text_bottom pb-sm-3 pl-xl-3">
                                            <div class="sfsi_into_time_text_bottom_counter">
                                                <div class="sfsi_intro_premium_counter_hours">

                                                </div>
                                                <span style="margin-left: 4px;">Hours</span>
                                            </div>
                                            <div class="sfsi_into_time_text_bottom_counter">
                                                <div class="sfsi_intro_premium_counter_min">

                                                </div>
                                                <span>Minutes</span>
                                            </div>

                                            <div>
                                                <div class="sfsi_intro_premium_counter_sec">

                                                </div>
                                                <span>Seconds</span>
                                            </div>

                                        </div>

                                        <div>
                                            <div class="sfsi_intro_button ml-xl-3" style="margin-right: -8px;">
                                                <a class="btn btn-sm" href="https://www.ultimatelysocial.com/usm-premium/?withqp=1&discount=NEWINSTALL&utm_source=usmi_global&utm_campaign=new_installs&utm_medium=banner" target="_blank">Get it now >></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="row py-3">
                            <div class="col-12 col-md-2 col-lg-2 col-xxl-2 d-lg-flex justify-content-lg-end pr-lg-0">
                                <img src="<?php echo SFSI_PLUGURL . "images/new_intro_icons/popup.png"; ?>" class="sfsi_intro_images" alt="popup" />
                            </div>
                            <div class="col-12 col-md-10 col-lg-10 col-xxl-10 py-lg-2 sfsi_intro_text_section px-sm-4 pl-lg-0 pr-xl-0 pr-xl-2">
                                <div class="font-weight-bold sfsi_intro_sub_heading">Pop-ups:</div>
                                <div class="d-lg-flex justify-content-lg-between justify-content-xl-between">
                                    <div class="sfsi_intro_sub_text pb-sm-3">
                                        <div>For all your pop-up needs (to get subscribers, show discount </div>
                                        <div>codes, cookie notices etc.) we now have a separate service for it:</div>
                                    </div>
                                    <div>
                                        <div class="sfsi_intro_button ml-xl-3">
                                            <a class="btn btn-lg" href="https://sellcodes.com/s/3NmlIE" target="_blank">Check out MyPopUps >></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <h4>
                                <?php $nonce1 = wp_create_nonce("show_intro"); ?>
                                <span style="font-weight: 600;cursor: pointer;" data-nonce="<?php echo $nonce1; ?>" id="sfsi_intro_btn_ok_got_it_id" class="sfsi_intro_btn_ok_got_it">Ok, Got it</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function pad(d) {
                return (d < 10) ? '0' + d.toString() : d.toString();
            }
            window.countDownDate = <?php echo ($sfsi_max_show_time * 1000) ?>;
            window.difference = <?php echo (($sfsi_max_show_time  - $sfsi_current_date)*1000) ?>;
            console.log(window.difference)
            if( window.difference <= 0){
                    SFSI(".sfsi_intro_special_discount").hide();
            }else{
                window.countDownStopDate = new Date().getTime() + window.difference;
                var sfsi_firsttime_timerId = window.setInterval(function() {
                var d = new Date();
                var distance = window.countDownStopDate - (new Date().getTime());
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                var counters_hour = document.getElementsByClassName("sfsi_intro_premium_counter_hours");
                var counters_sec = document.getElementsByClassName("sfsi_intro_premium_counter_sec");
                var counters_min = document.getElementsByClassName("sfsi_intro_premium_counter_min");

                if (counters_min.length > 0) {
                    var counters_min = counters_min[0];
                    window.sfsi_intro_time_in_min = pad(minutes);
                    counters_min.innerText = window.sfsi_intro_time_in_min;
                }
                if (counters_sec.length > 0) {
                    var counters_sec = counters_sec[0];
                    window.sfsi_intro_premium_counter_sec = pad(seconds);
                    counters_sec.innerText = window.sfsi_intro_premium_counter_sec;
                }
                if (counters_hour.length > 0) {
                    var counters_hour = counters_hour[0];
                    window.sfsi_intro_premium_counter_hours = pad(hours);
                    counters_hour.innerText = window.sfsi_intro_premium_counter_hours;
                }
                if (distance < 0) {
                    SFSI(".sfsi_intro_special_discount").hide();
                    clearInterval(sfsi_firsttime_timerId);
                }
            }, 1000);
            }
            
        </script>
        <div class="row">
            <div class="col-12 col-md-9 col-lg-12 sfsi_intro_section2" style="<?php echo ($sfsi_new_intro_banner_hide_option['sfsi_display_section2'] == "true") ?  'display:block !important;' : 'display:none!important;'; ?>">
                <h1 class="d-inline-block">Ultimate Social Media plugin</h1>
                <?php $nonce = wp_create_nonce("hide_intro"); ?>
                <span class="sfsi_intro_btn_show_intro text-success-new pl-2" id="sfsi_intro_btn_show_intro_id" data-nonce="<?php echo $nonce; ?>" style="<?php echo ($sfsi_new_intro_banner_hide_option['sfsi_display_section'] == "true") ?  'display:none!important;' : 'display:inline-block !important;'; ?>">Show intro</span>
                <div class="">
                    <div class="row">
                        <div class="d-lg-flex col-12 col-lg-4 col-xxl-3 pr-lg-0 ">
                            <div class='d-table sfsi_intro_bg_white' style='width:100%;height:100%'>
                                <div class='d-table-row'>
                                    <div class='d-table-cell'>
                                        <a href="https://www.ultimatelysocial.com/usm-premium/?playvideo=1&utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" target="_blank"><img target="_blank" alt="video" src="<?php echo SFSI_PLUGURL; ?>images/sfsi-video-play.png" style='width:100%'></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5 col-xxl-5 sfsi_intro_bg_xxl_white sfsi_intro_bg_xxl_white_check">
                            <div class="sfsi_intro_bg_white" style="height: 100%;">
                                <div class="d-lg-flex align-items-xl-center row" style="height: inherit;">
                                    <div class="col-12 col-xxl-12">
                                        <p class="sfsi-top-banner-higligted-text pr-2 line-h-30" style="border: 1px solid white;line-height: 23px;font-family: 'montserrat-regular';text-align: justify;min-height: 88px;">If you want
                                            <span class='font-weight-bold sfsi_intro_sub_heading'>more likes & shares</span>, more placement options, better sharing features (eg: define the text and image that gets shared), optimization for mobile,
                                            <a target="_blank" href="https://www.ultimatelysocial.com/extra-icon-styles/?utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" class=" text-success-new" style="font-family: 'montserrat-regular';">more icon design styles,</a>
                                            <a target="_blank" href="https://www.ultimatelysocial.com/animated-social-media-icons/?utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" class=" text-success-new " style="font-family:'montserrat-regular'">animated icons,</a>
                                            <a target="_blank" href="https://www.ultimatelysocial.com/themed-icons-search/?utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" class=' text-success-new ' style="font-family: 'montserrat-regular';">themed icons,</a> and
                                            <a href="https://www.ultimatelysocial.com/themed-icons-search/?utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" target="_blank" class=" text-success-new  " style="font-family: 'montserrat-regular';">much more</a>, then
                                            <a href="https://www.ultimatelysocial.com/usm-premium/?withqp=1&utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" class="sfsi_intro_sub_heading text-success-new" style="cursor:pointer; text-decoration: none !important;font-weight: bold;" target="_blank">
                                                go premium</a>.</p>
                                        <div class="col-12 text-center col-xxl-12">
                                            <div class='d-table' style='width:100%;height:100%'>
                                                <div class='d-table-row'>
                                                    <div class='d-table-cell align-middle'>
                                                        <div>
                                                            <a target="_blank" href="https://www.ultimatelysocial.com/usm-premium/?withqp=1&utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" class="btn btn-success-new sfsi_intro_btn_go_premium">Go Premium</a>
                                                        </div>
                                                        <a href="https://www.ultimatelysocial.com/usm-premium/?utm_source=usmi_settings_page&utm_campaign=top_banner&utm_medium=link" style="text-decoration: none;color:#414951;font-family: 'montserrat-regular';opacity: 0.7;" target='_blank'>Learn more</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 col-xxl-3 d-lg-flex justify-content-lg-center align-items-lg-center">
                            <div class="d-lg-flex row sfsi_intro_bg_question justify-content-lg-center" style="font-size: 17px;">
                                <div class="col-12 col-xxl-12" style="min-height: 189px;">
                                    <div class="font-weight-bold sfsi_intro_sub_heading pt-3">Any Questions?</div>
                                    <div style="font-family:montserrat-medium;line-height: 23px;min-height: 88px;">
                                        <div>Just ask us in the support forum.</div>
                                        <div class="pr-2">We'll try to respond quickly </div>
                                        <div class="pr-2 pb-3">& for free! :)</div>
                                    </div>
                                    <div class="col-12 text-center px-0 col-xxl-12">
                                        <div class='d-table' style='width:100%;height:100%'>
                                            <div class='d-table-row'>
                                                <div class="d-table-cell align-middle">
                                                    <div>
                                                        <a target="_blank" href="http://bit.ly/USM_SUPPORT_FORUM" class="btn btn-success sfsi_intro_btn_question">Open a support thread</a>
                                                    </div>
                                                    <a href="#" class="sfsi_intro_tooltip" title="Your account on Wordpress.org (where you can give your review) is a different to the one you login to your WordPress dashboard (where you are now). If you don’t have a WordPress.org account yet, please sign up at the top right on the Support Forum page, and then scroll down on that page. It only takes a minute :) Thank you!" style="font-size: 13px;text-decoration: none;color:#414951;font-family: 'montserrat-regular';opacity: 0.7;">Trouble logging in there?</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 d-lg-none">
                <!-- <div style="position:relative;padding-top:56.25%;">
                <iframe src="https://video.inchev.com/videos/embed/c952d896-34be-45bc-8142-ba14694c1bd0" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
            </div> -->
                <!-- <div class="text-center mt-5">
                <div class='sfsi-premium-btn'>
                    <button class="btn btn-success ">Go Premium</button>
                </div>
                <span>Learn more</span>
            </div> -->
            </div>
        </div>
    </div><!-- END Top content area of plugin -->

    <!-- step 1 end  here -->
    <div id="accordion">

        <h3><span>1</span>Which icons do you want to show on your site? </h3>
        <!-- step 1 end  here -->
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view1.php'); ?>
        <!-- step 1 end here -->

        <!-- step 2 start here -->
        <h3><span>2</span>What do you want the icons to do? </h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view2.php'); ?>
        <!-- step 2 END here -->

        <!-- step 3 start here -->
        <h3><span>3</span>Where shall they be displayed? </h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_question3.php'); ?>
        <!-- step 3 end here -->


    </div>

    <h2 class="optional">Optional</h2>

    <div id="accordion1">

        <!-- step 4 start here -->
        <h3><span>4</span>What design &amp; animation do you want to give your icons?</h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view3.php'); ?>
        <!-- step 4 END here -->

        <!-- step 5 Start here -->
        <h3><span>5</span>Do you want to display "counts" next to your icons?</h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view4.php'); ?>
        <!-- step 5 END here -->

        <!-- step 6 Start here -->
        <h3><span>6</span>Any other wishes for your main icons?</h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view5.php'); ?>
        <!-- step 6 END here -->

        <!-- step 7 Start here -->
        <h3><span>7</span>Do you want to display a pop-up, asking people to subscribe?</h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view7.php'); ?>
        <!-- step 7 END here -->

        <!-- step 8 Start here -->
        <h3><span>8</span>Do you want to show a subscription form (<b>increases sign ups</b>)?</h3>
        <?php include(SFSI_DOCROOT . '/views/sfsi_option_view8.php'); ?>
        <!-- step 8 END here -->

    </div>

    <div class="tab10">
        <div class="save_export">
            <div class="save_button">

                <img src="<?php echo SFSI_PLUGURL; ?>images/ajax-loader.gif" class="loader-img" alt="error" />

                <a href="javascript:;" id="save_all_settings" title="Save All Settings">Save All Settings</a>

            </div>
            <?php $nonce = wp_create_nonce("sfsi_save_export"); ?>

            <div class="export_selections">
                <div class="export" id="sfsi_save_export" data-nonce="<?php echo $nonce; ?>">
                    Export
                </div>

                <div>selections</div>

            </div>
        </div>
        <p class="red_txt errorMsg" style="display:none;font-size:21px"> </p>
        <p class="green_txt sucMsg" style="display:none;font-size:21px"> </p>

        <?php // include(SFSI_DOCROOT . '/views/sfsi_affiliate_banner.php'); 
        ?><?php include(SFSI_DOCROOT . '/views/sfsi_section_for_premium.php'); ?>

        <!--<p class="bldtxtmsg">Need top-notch Wordpress development work at a competitive price? Visit us at <a href="https://www.ultimatelysocial.com/usm-premium/?utm_source=usmi_settings_page&utm_campaign=footer_credit&utm_medium=banner">ultimatelysocial.com</a></p>-->
    </div>
    <!-- all pops of plugin under sfsi_pop_content.php file -->
    <?php include(SFSI_DOCROOT . '/views/sfsi_pop_content.php'); ?>

</div> <!-- START Admin view for plugin-->
<?php if (in_array(get_site_url(), array('http://www.managingio.com', 'http://blog-latest.socialshare.com'))) : ?>
    <div style="text-align:center">
        <input type="text" name="domain" id="sfsi_domain_input" style="width:40%;min-height: :40px;text-align:center;margin:0 auto" placeholder="Enter Domian to check its theme" />
        <input type="text" name="sfsi_domain_input_nonce" value="<?php echo wp_create_nonce('bannerOption'); ?>">
        <div class="save_button">
            <img src="<?php echo SFSI_PLUGURL; ?>images/ajax-loader.gif" class="loader-img" alt="error" />
            <a href="javascript:;" id="sfsi_check_theme_of_domain_btn" title="Check">Check the Theme</a>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#sfsi_check_theme_of_domain_btn').click(function() {
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "post",
                        data: {
                            'action': 'bannerOption',
                            'domain': $('#sfsi_domain_input').val(),
                            'nonce': $('#sfsi_domain_input_nonce').val(),
                        },
                        success: function(s) {
                            var sfsi_container = $("html,body");
                            var sfsi_scrollTo = $('.sfsi_notificationBannner');
                            $('.sfsi_notificationBannner').attr('tabindex', $('.sfsi_notificationBannner').attr('tabindex') || -1);
                            jQuery(".sfsi_notificationBannner").html(s).focus();
                            sfsi_container.animate({
                                scrollTop: (sfsi_scrollTo.offset().top - sfsi_container.offset().top + sfsi_container.scrollTop()),
                                scrollLeft: 0
                            }, 300);

                        }
                    });
                });
            })
        </script>
    <?php endif; ?>
    <script type="text/javascript">
        var e = {
            action: "bannerOption",
            'nonce': '<?php echo wp_create_nonce('bannerOption'); ?>',

        };
        jQuery.ajax({
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            type: "post",
            data: e,
            success: function(s) {
                jQuery(".sfsi_notificationBannner").html(s);
            }
        });
    </script>
    <?php include(SFSI_DOCROOT . '/views/sfsi_chat_on_admin_pannel.php'); ?>