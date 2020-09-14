<?php

namespace Booking_System\Utilities;

use Booking_System\Helpers;

class Booking_Form {

	private $product_id;
	private $is_bookable;
	private $booking_setting_id;
	private $booking_settings;

	public function __construct( $product_id ) {
		$this->product_id = $product_id;
		$this->is_bookable();
		$this->booking_setting_id();
		$this->booking_settings = new Booking_Settings( $this->booking_setting_id );
	}

	public function is_bookable() {
		$this->is_bookable = get_field( 'bookable', $this->product_id );
	}

	private function booking_setting_id() {
		$this->booking_setting_id = get_field( 'booking_option', $this->product_id );
	}

	private function persons_count_field( $field_id, $name ) {
		$min         = $this->booking_settings->get_min_persons();
		$max         = $this->booking_settings->get_max_persons();
		$extra       = $this->booking_settings->is_allowed_extra_persons();
		$atts        = array();
		$atts['min'] = $min;
		if ( ! $extra ) {
			$atts['max'] = $max;
		}
		?>
        <div class="wcb-persons-number">
            <label for="<?php echo esc_attr( $field_id ) ?>">
				<?php _e( 'Number of persons', 'wcb' ); ?>
            </label>
            <input type="number" id="<?php echo esc_attr( $field_id ) ?>" name="<?php echo esc_attr( $name ) ?>" <?php echo Helpers::build_attributes( $atts ); ?>>
        </div>
		<?php
	}

	private function product_id_field() {
		?>
        <input type="hidden" name="wcb_product_id" value="<?php echo esc_attr( $this->product_id ) ?>">
		<?php
	}

	private function calendar_field() {
		?>
        <label for="wcd_date_picker">
			<?php _e( 'Event Date', 'wcb' ) ?>
        </label>
        <input type="text" id="wcd_date_picker" class="wcd_flat_calendar" name="wcd_date">
		<?php
		$this->calendar_init();
	}

	private function calendar_init() {
		$allowed_days    = $this->booking_settings->get_available_days();
		$week_days       = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
		$disallowed_days = array_diff( $week_days, $allowed_days );
		?>
        <script>
            (function ($) {
                var disallowed_days = JSON.parse('<?php echo json_encode( $disallowed_days )?>');
                $(".wcd_flat_calendar").flatpickr({
                    "minDate": 'today',
                    "disable": [
                        function (date) {
                            if (date.getDay() in disallowed_days) {
                                return true;
                            }
                        }
                    ]
                });
            })(jQuery);
        </script>
		<?php
	}

	private function time_select_field( $field_id, $name ) {
		$allowed_hours = $this->booking_settings->get_available_hours();
		?>
        <div class="wcb-select-time">
            <label for="<?php echo esc_attr( $field_id ) ?>">
				<?php _e( 'Select Time', 'wcb' ); ?>
            </label>
            <select name="<?php echo esc_attr( $name ) ?>" id="<?php echo esc_attr( $field_id ) ?>">
				<?php foreach ( $allowed_hours as $allowed_hour ) { ?>
                    <option value="<?php echo esc_attr( $allowed_hour ); ?>">
						<?php echo esc_html( $allowed_hour ); ?>
                    </option>
				<?php } ?>
            </select>
        </div>
		<?php
	}

	public function generate_booking_form() {
		if ( $this->is_bookable && $this->booking_settings->is_setting_available() ) {
			$this->persons_count_field( 'wcd_persons_count', 'wcd_persons_count' );
			$this->product_id_field();
			$this->calendar_field();
			$this->time_select_field( 'wcb_time_select', 'wcb_time_select' );
		}
	}
}