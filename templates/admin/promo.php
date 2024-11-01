<?php

$is_hidden = isset( $is_hidden ) && $is_hidden;

$transient_key  = 'wptv_promo_time';
$countdown_time = get_transient( $transient_key );

if ( ! $countdown_time ) {

	$date = date( 'Y-m-d-H-i', strtotime( '+ 14 Hours' ) );

	$date_parts = explode( '-', $date );

	$countdown_time = [
		'year'   => $date_parts[0],
		'month'  => $date_parts[1],
		'day'    => $date_parts[2],
		'hour'   => $date_parts[3],
		'minute' => $date_parts[4],
	];

	set_transient( $transient_key, $countdown_time, 6 * 24 * HOUR_IN_SECONDS );

}

?>

<style>
    .syotimer {
        text-align: center;
        margin: 15px 0 0;
        padding: 0 0 10px;
    }

    .syotimer-cell {
        display: inline-block;
        margin: 0 5px;

        width: 60px;
        background: url(<?php echo WPTV_ASSETS.'images/timer.png'; ?>) no-repeat;
        background-size: contain;
    }

    .syotimer-cell__value {
        font-size: 35px;
        color: orangered;
        height: 60px;
        line-height: 60px;
        margin: 0 0 5px;
    }

    .syotimer-cell__unit {
        font-family: Arial, serif;
        font-size: 12px;
        text-transform: uppercase;
        color: #fff;
    }

    .wptv-promo .limited-time{
        margin: 0;
    }

    .wptv-promo a{
        margin-top: 0;
    }
    .wptv-promo .coupon{
        color: #FBBF00;
    }

    .wptv-promo .promo-img{
        height: 100px;
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px;
        border-radius: 50%;
    }

    .wptv-promo .promo-title{
        margin-top: -10px;
    }
</style>
<div class="wptv-promo <?php echo $is_hidden ? 'hidden' : ''; ?>">
    <div class="wptv-promo-inner">

		<?php if ( $is_hidden ) { ?>
            <span class="close-promo">&times;</span>
		<?php } ?>
        <img src="<?php echo WPTV_ASSETS . 'images/crown.svg'; ?>" class="promo-img">

        <h3 class="promo-title">Unlock the PRO features</h3>
        <h3><span class="coupon">50% OFF</span></h3>
        <h3 class="limited-time" style="font-size: 18px;">LIMITED TIME ONLY</h3>
        <div class="simple_timer"></div>
        <a href="<?php echo WPTV_PRICING; ?>">GET PRO</a>
    </div>
</div>
<script>
    (function ($) {
        $(document).ready(function () {

            $(document).on('click', '.close-promo', function () {
                $(this).closest('.wptv-promo').addClass('hidden');
            });

            if (typeof window.timer_set === 'undefined') {
                window.timer_set = $('.simple_timer').syotimer({
                    year: <?php echo $countdown_time['year']; ?>,
                    month: <?php echo $countdown_time['month']; ?>,
                    day: <?php echo $countdown_time['day']; ?>,
                    hour: <?php echo $countdown_time['hour']; ?>,
                    minute: <?php echo $countdown_time['minute']; ?>
                });
            }
        })
    })(jQuery);
</script>