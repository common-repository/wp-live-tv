<?php

/** prevent direct access */
defined( 'ABSPATH' ) || exit();

if ( ! function_exists( 'wptv_get_meta' ) ) {
	/**
	 * Get post meta value. Default value return if meta value is empty
	 *
	 * @param $post_id
	 * @param $key
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	function wptv_get_meta( $post_id, $key, $default = '' ) {
		$meta = get_post_meta( $post_id, $key, true );

		return ! empty( $meta ) ? $meta : $default;
	}
}

if ( ! function_exists( 'wptv_get_country' ) ) {
	/**
	 * Get station country
	 *
	 * @param $country_code
	 *
	 * @return array|bool|false|WP_Term
	 */
	function wptv_get_country( $country_code ) {
		$term = get_term_by( 'slug', $country_code, 'tv_country' );

		return $term ? $term : false;
	}
}

if ( ! function_exists( 'wptv_get_country_flag' ) ) {
	/**
	 * Get country flag image url
	 *
	 * @param $country_code
	 * @param int $size
	 *
	 * @return string
	 */
	function wptv_get_country_flag( $country_code, $size = 16, $lazyload = false ) {
		if ( strlen( $country_code ) != 2 ) {
			return '';
		}

		$url = WPTV_ASSETS . "/images/flags/${country_code}.svg";
		if ( $lazyload ) {
			return sprintf( '<img class="wptv-lazy-load" data-src="%s" width="%s">', $url, $size );
		}

		return sprintf( '<img src="%s" width="%s">', $url, $size );
	}
}

if ( ! function_exists( 'wptv_get_channels' ) ) {
	/**
	 * Get stations
	 *
	 * @param array $args
	 * @param bool $return_query
	 *
	 * @return array|bool|WP_Query
	 */
	function wptv_get_channels( $args = [], $return_query = false ) {

		$posts_per_page = wptv_get_settings( 'posts_per_page', 10, 'wptv_display_settings' );

		$args = wp_parse_args( $args, [
			                       'post_type'      => 'wptv',
			                       'posts_per_page' => $posts_per_page,
			                       'orderby'        => 'title',
			                       'order'          => 'ASC',
		                       ] );

		if ( ! empty( $_REQUEST['keyword'] ) ) {
			$args['s'] = sanitize_text_field( $_REQUEST['keyword'] );
		}

		if ( ! empty( $_REQUEST['paginate'] ) ) {
			$args['offset'] = ( intval( $_REQUEST['paginate'] ) - 1 ) * $posts_per_page;
		}

		$query = new WP_Query( $args );

		if ( $return_query ) {
			return $query;
		}

		return $query->have_posts() ? $query->posts : false;
	}
}

if ( ! function_exists( 'wptv_get_settings' ) ) {
	/**
	 * Get setting database option
	 *
	 * @param $section
	 * @param $key
	 * @param string $default
	 *
	 * @return string
	 */
	function wptv_get_settings( $key, $default = '', $section = 'wptv_general_settings' ) {
		$settings = get_option( $section );

		return ! empty( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}

if ( ! function_exists( 'wptv_get_channel_categories' ) ) {
	/**
	 * Get station categories html
	 *
	 * @param $post_id
	 *
	 * @return boolean|string
	 * @since 1.0.0
	 *
	 */
	function wptv_get_channel_categories( $post_id ) {
		$categories = wp_get_post_terms( $post_id, 'tv_category' );

		$country = get_the_terms( $post_id, 'tv_country' );

		$country = ! empty( $country[0] ) ? $country[0]->term_id : '';

		if ( ! empty( $categories ) ) {
			ob_start();
			$categories = wp_list_pluck( $categories, 'name', 'term_id' );
			?>
            <div class="categories">
                <span><?php esc_html_e( 'Categories:', 'wp-live-tv' ); ?></span>
				<?php
				foreach ( $categories as $term_id => $cat ) { ?>
                    <a href="<?php echo add_query_arg( 'country', $country, get_term_link( $term_id ) ); ?>">
                        <span><?php echo $cat; ?></span>
                    </a>
				<?php }
				?>
            </div>
			<?php
			return ob_get_clean();
		}

		return false;
	}
}

if ( ! function_exists( 'wptv_get_file_type_by_url' ) ) {
	/**
	 * Get the file mime type for a file by its URL.
	 *
	 * @param $url .
	 *
	 * @return string.
	 */
	function wptv_get_file_type_by_url( $url ) {

		$response = wp_remote_get( $url );

		return wp_remote_retrieve_header( $response, 'content-type' );

	}
}

function wptv_country_list( $code = null ) {
	$countries = array(
		"AF" => "Afghanistan",
		"AL" => "Albania",
		"DZ" => "Algeria",
		"AS" => "American Samoa",
		"AD" => "Andorra",
		"AO" => "Angola",
		"AI" => "Anguilla",
		"AQ" => "Antarctica",
		"AG" => "Antigua and Barbuda",
		"AR" => "Argentina",
		"AM" => "Armenia",
		"AW" => "Aruba",
		"AU" => "Australia",
		"AT" => "Austria",
		"AZ" => "Azerbaijan",
		"BS" => "Bahamas",
		"BH" => "Bahrain",
		"BD" => "Bangladesh",
		"BB" => "Barbados",
		"BY" => "Belarus",
		"BE" => "Belgium",
		"BZ" => "Belize",
		"BJ" => "Benin",
		"BM" => "Bermuda",
		"BT" => "Bhutan",
		"BO" => "Bolivia",
		"BA" => "Bosnia and Herzegovina",
		"BW" => "Botswana",
		"BV" => "Bouvet Island",
		"BR" => "Brazil",
		"IO" => "British Indian Ocean Territory",
		"BN" => "Brunei Darussalam",
		"BG" => "Bulgaria",
		"BF" => "Burkina Faso",
		"BI" => "Burundi",
		"KH" => "Cambodia",
		"CM" => "Cameroon",
		"CA" => "Canada",
		"CV" => "Cape Verde",
		"KY" => "Cayman Islands",
		"CF" => "Central African Republic",
		"TD" => "Chad",
		"CL" => "Chile",
		"CN" => "China",
		"CX" => "Christmas Island",
		"CC" => "Cocos (Keeling) Islands",
		"CO" => "Colombia",
		"KM" => "Comoros",
		"CG" => "Congo",
		"CD" => "Congo, the Democratic Republic of the",
		"CK" => "Cook Islands",
		"CR" => "Costa Rica",
		"CI" => "Cote D'Ivoire",
		"HR" => "Croatia",
		"CU" => "Cuba",
		"CY" => "Cyprus",
		"CZ" => "Czech Republic",
		"DK" => "Denmark",
		"DJ" => "Djibouti",
		"DM" => "Dominica",
		"DO" => "Dominican Republic",
		"EC" => "Ecuador",
		"EG" => "Egypt",
		"SV" => "El Salvador",
		"GQ" => "Equatorial Guinea",
		"ER" => "Eritrea",
		"EE" => "Estonia",
		"ET" => "Ethiopia",
		"FK" => "Falkland Islands (Malvinas)",
		"FO" => "Faroe Islands",
		"FJ" => "Fiji",
		"FI" => "Finland",
		"FR" => "France",
		"GF" => "French Guiana",
		"PF" => "French Polynesia",
		"TF" => "French Southern Territories",
		"GA" => "Gabon",
		"GM" => "Gambia",
		"GE" => "Georgia",
		"DE" => "Germany",
		"GH" => "Ghana",
		"GI" => "Gibraltar",
		"GR" => "Greece",
		"GL" => "Greenland",
		"GD" => "Grenada",
		"GP" => "Guadeloupe",
		"GU" => "Guam",
		"GT" => "Guatemala",
		"GN" => "Guinea",
		"GW" => "Guinea-Bissau",
		"GY" => "Guyana",
		"HT" => "Haiti",
		"HM" => "Heard Island and Mcdonald Islands",
		"VA" => "Holy See (Vatican City State)",
		"HN" => "Honduras",
		"HK" => "Hong Kong",
		"HU" => "Hungary",
		"IS" => "Iceland",
		"IN" => "India",
		"ID" => "Indonesia",
		"IR" => "Iran, Islamic Republic of",
		"IQ" => "Iraq",
		"IE" => "Ireland",
		"IL" => "Israel",
		"IT" => "Italy",
		"JM" => "Jamaica",
		"JP" => "Japan",
		"JO" => "Jordan",
		"KZ" => "Kazakhstan",
		"KE" => "Kenya",
		"KI" => "Kiribati",
		"KP" => "Korea, Democratic People's Republic of",
		"KR" => "Korea, Republic of",
		"KW" => "Kuwait",
		"KG" => "Kyrgyzstan",
		"LA" => "Lao People's Democratic Republic",
		"LV" => "Latvia",
		"LB" => "Lebanon",
		"LS" => "Lesotho",
		"LR" => "Liberia",
		"LY" => "Libyan Arab Jamahiriya",
		"LI" => "Liechtenstein",
		"LT" => "Lithuania",
		"LU" => "Luxembourg",
		"MO" => "Macao",
		"MK" => "Macedonia, the Former Yugoslav Republic of",
		"MG" => "Madagascar",
		"MW" => "Malawi",
		"MY" => "Malaysia",
		"MV" => "Maldives",
		"ML" => "Mali",
		"MT" => "Malta",
		"MH" => "Marshall Islands",
		"MQ" => "Martinique",
		"MR" => "Mauritania",
		"MU" => "Mauritius",
		"YT" => "Mayotte",
		"MX" => "Mexico",
		"FM" => "Micronesia, Federated States of",
		"MD" => "Moldova, Republic of",
		"MC" => "Monaco",
		"MN" => "Mongolia",
		"MS" => "Montserrat",
		"MA" => "Morocco",
		"MZ" => "Mozambique",
		"MM" => "Myanmar",
		"NA" => "Namibia",
		"NR" => "Nauru",
		"NP" => "Nepal",
		"NL" => "Netherlands",
		"AN" => "Netherlands Antilles",
		"NC" => "New Caledonia",
		"NZ" => "New Zealand",
		"NI" => "Nicaragua",
		"NE" => "Niger",
		"NG" => "Nigeria",
		"NU" => "Niue",
		"NF" => "Norfolk Island",
		"MP" => "Northern Mariana Islands",
		"NO" => "Norway",
		"OM" => "Oman",
		"PK" => "Pakistan",
		"PW" => "Palau",
		"PS" => "Palestinian Territory, Occupied",
		"PA" => "Panama",
		"PG" => "Papua New Guinea",
		"PY" => "Paraguay",
		"PE" => "Peru",
		"PH" => "Philippines",
		"PN" => "Pitcairn",
		"PL" => "Poland",
		"PT" => "Portugal",
		"PR" => "Puerto Rico",
		"QA" => "Qatar",
		"RE" => "Reunion",
		"RO" => "Romania",
		"RU" => "Russian Federation",
		"RW" => "Rwanda",
		"SH" => "Saint Helena",
		"KN" => "Saint Kitts and Nevis",
		"LC" => "Saint Lucia",
		"PM" => "Saint Pierre and Miquelon",
		"VC" => "Saint Vincent and the Grenadines",
		"WS" => "Samoa",
		"SM" => "San Marino",
		"ST" => "Sao Tome and Principe",
		"SA" => "Saudi Arabia",
		"SN" => "Senegal",
		"CS" => "Serbia and Montenegro",
		"SC" => "Seychelles",
		"SL" => "Sierra Leone",
		"SG" => "Singapore",
		"SK" => "Slovakia",
		"SI" => "Slovenia",
		"SB" => "Solomon Islands",
		"SO" => "Somalia",
		"ZA" => "South Africa",
		"GS" => "South Georgia and the South Sandwich Islands",
		"ES" => "Spain",
		"LK" => "Sri Lanka",
		"SD" => "Sudan",
		"SR" => "Suriname",
		"SJ" => "Svalbard and Jan Mayen",
		"SZ" => "Swaziland",
		"SE" => "Sweden",
		"CH" => "Switzerland",
		"SY" => "Syrian Arab Republic",
		"TW" => "Taiwan, Province of China",
		"TJ" => "Tajikistan",
		"TZ" => "Tanzania, United Republic of",
		"TH" => "Thailand",
		"TL" => "Timor-Leste",
		"TG" => "Togo",
		"TK" => "Tokelau",
		"TO" => "Tonga",
		"TT" => "Trinidad and Tobago",
		"TN" => "Tunisia",
		"TR" => "Turkey",
		"TM" => "Turkmenistan",
		"TC" => "Turks and Caicos Islands",
		"TV" => "Tuvalu",
		"UG" => "Uganda",
		"UA" => "Ukraine",
		"AE" => "United Arab Emirates",
		"GB" => "United Kingdom",
		"US" => "United States",
		"UM" => "United States Minor Outlying Islands",
		"UY" => "Uruguay",
		"UZ" => "Uzbekistan",
		"VU" => "Vanuatu",
		"VE" => "Venezuela",
		"VN" => "Viet Nam",
		"VG" => "Virgin Islands, British",
		"VI" => "Virgin Islands, U.s.",
		"WF" => "Wallis and Futuna",
		"EH" => "Western Sahara",
		"YE" => "Yemen",
		"ZM" => "Zambia",
		"ZW" => "Zimbabwe"
	);

	return $code ? $countries[ $code ] : $countries;

}

function wptv_country_channels_map() {
	$wptv_country_map = [
		'ww' => [ 'country' => 'No Country', 'count' => 18 ],
		'af' => [ 'country' => 'Afghanistan', 'count' => 3 ],
		'za' => [ 'country' => 'Africa', 'count' => 2 ],
		'au' => [ 'country' => 'Australia', 'count' => 5 ],
		'at' => [ 'country' => 'Austria', 'count' => 1 ],
		'bd' => [ 'country' => 'Bangladesh', 'count' => 18 ],
		'cn' => [ 'country' => 'China', 'count' => 3 ],
		'cy' => [ 'country' => 'Cyprus', 'count' => 3 ],
		'eg' => [ 'country' => 'Egypt', 'count' => 8 ],
		'fr' => [ 'country' => 'France', 'count' => 10 ],
		'de' => [ 'country' => 'Germany', 'count' => 1 ],
		'gh' => [ 'country' => 'Ghana', 'count' => 2 ],
		'gr' => [ 'country' => 'Greece', 'count' => 3 ],
		'in' => [ 'country' => 'India', 'count' => 83 ],
		'ir' => [ 'country' => 'Iran', 'count' => 3 ],
		'iq' => [ 'country' => 'Iraq', 'count' => 1 ],
		'jo' => [ 'country' => 'Jordan', 'count' => 2 ],
		'ke' => [ 'country' => 'Kenya', 'count' => 3 ],
		'kr' => [ 'country' => 'Korea', 'count' => 4 ],
		'kw' => [ 'country' => 'Kuwait', 'count' => 3 ],
		'lb' => [ 'country' => 'Lebanon', 'count' => 9 ],
		'my' => [ 'country' => 'Malaysia', 'count' => 1 ],
		'ml' => [ 'country' => 'Mali', 'count' => 1 ],
		'ng' => [ 'country' => 'Nigeria', 'count' => 2 ],
		'pk' => [ 'country' => 'Pakistan', 'count' => 65 ],
		'qa' => [ 'country' => 'Qatar', 'count' => 2 ],
		'ru' => [ 'country' => 'Russia', 'count' => 5 ],
		'sa' => [ 'country' => 'Saudi Arabia', 'count' => 11 ],
		'es' => [ 'country' => 'Spain', 'count' => 3 ],
		'lk' => [ 'country' => 'Sri Lanka', 'count' => 3 ],
		'sy' => [ 'country' => 'Syria', 'count' => 4 ],
		'tw' => [ 'country' => 'Taiwan', 'count' => 7 ],
		'tr' => [ 'country' => 'Turkey', 'count' => 12 ],
		'ae' => [ 'country' => 'United Arab Emirates', 'count' => 6 ],
		'ug' => [ 'country' => 'Uganda', 'count' => 3 ],
		'gb' => [ 'country' => 'United Kingdom', 'count' => 22 ],
		'ua' => [ 'country' => 'Ukraine', 'count' => 2 ],
		'us' => [ 'country' => 'United States', 'count' => 61 ],
		've' => [ 'country' => 'Venezuela', 'count' => 5 ],
	];

	return $wptv_country_map;
}