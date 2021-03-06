<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Require Carbon Fields
 */

add_action( 'after_setup_theme', 'pdp_carbon_fields_load' );

function pdp_carbon_fields_load(){
	require_once( 'vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}


/**
 *  Admin Menu Pages
 */

add_action( 'admin_menu', function(){
	add_submenu_page(
		'crb_carbon_fields_container_pied-de-poule.php',
		__( 'Синхронизация цен', 'pdp' ),
		__( 'Синхронизация цен', 'pdp' ),
		'manage_options',
		'google-api-settings',
		'pdp_admin_page_pricelists_sync'
	);

	add_submenu_page(
		'crb_carbon_fields_container_pied-de-poule.php',
		__( 'Лента Instagram', 'pdp' ),
		__( 'Лента Instagram', 'pdp' ),
		'manage_options',
		'pdp-instagram-sync',
		'pdp_admin_page_instagram_feed'
	);
}, 11 );

function pdp_admin_page_pricelists_sync(){
    require PDP_PLUGIN_PATH . 'templates/pricelists-sync.php';
}

function pdp_admin_page_instagram_feed(){
	require PDP_PLUGIN_PATH . 'templates/instagram-feed.php';
}


/**
 *  Menus creation page fixes.
 */

add_action( 'load-nav-menus.php', 'pdp_init_menus_creation_page_fixes' );

function pdp_init_menus_creation_page_fixes(){
	add_action( 'pre_get_posts', 'pdp_disable_paging_for_hierarchical_post_types' );
	add_filter( 'get_terms_args', 'pdp_remove_limit_for_hierarchical_taxonomies', 10, 2 );
	add_filter( 'get_terms_fields', 'pdp_remove_page_links_for_hierarchical_taxonomies', 10, 3 );
}

function pdp_disable_paging_for_hierarchical_post_types( $query ){
	if( !is_admin() || 'nav-menus' !== get_current_screen()->id ){
		return;
	}

	if( !is_post_type_hierarchical( $query->get( 'post_type' ) ) ){
		return;
	}

	if( 50 == $query->get( 'posts_per_page' ) ){
		$query->set( 'nopaging', true );
	}
}

function pdp_remove_limit_for_hierarchical_taxonomies( $args, $taxonomies ){
	if( !is_admin() || 'nav-menus' !== get_current_screen()->id ){
		return $args;
	}

	if( !is_taxonomy_hierarchical( reset( $taxonomies ) ) ){
		return $args;
	}

	if( 50 == $args['number'] ){
		$args['number'] = '';
	}

	return $args;
}

function pdp_remove_page_links_for_hierarchical_taxonomies( $selects, $args, $taxonomies ){
	if( !is_admin() || 'nav-menus' !== get_current_screen()->id ){
		return $selects;
	}

	if( !is_taxonomy_hierarchical( reset( $taxonomies ) ) ){
		return $selects;
	}

	if( 'count' === $args['fields'] ){
		$selects = array( '1' );
	}

	return $selects;
}


function pdp_get_salons_pricelist_id(){
	$salons = pdp_get_salons( 'ASC', 'all' );

	$data = [];

	foreach( $salons as $salon ) {
		$spreadsheet_id = carbon_get_post_meta( $salon->ID, 'pricelist_sheet_id' );
		if( !empty( $spreadsheet_id ) ){
			$data[] = array(
				'salon_id'          => $salon->ID,
				'spreadsheet_id'    => $spreadsheet_id
			);
		}
	}

	return $data;
}

function pdp_get_salon_pricelist_id( $salon_id = false ){
	if( !$salon_id ){
		return [];
	}

	$available_languages = pll_languages_list( ['hide_empty' => false] );
	$data = [];

	foreach( $available_languages as $language ){
		$id = pll_get_post( $salon_id, $language );
		$spreadsheet_id = carbon_get_post_meta( $id, 'pricelist_sheet_id' );

		if( !empty( $id ) && !empty( $spreadsheet_id ) ){
			$data[] = array(
				'salon_id'          => $id,
				'spreadsheet_id'    => $spreadsheet_id
			);
		}
	}

	return $data;
}

function pdp_cyr_to_lat( $str ){
	$cyr = [
		'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
		'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
		'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
	];

	$lat = [
		'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
		'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
		'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
		'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
	];

	return str_replace( $cyr, $lat, $str );
}

function pdp_service_slug_to_key( $str ){
	return str_replace( [' ', '/'], '-', mb_strtolower( pdp_cyr_to_lat( $str ) ) );
}


/**
 *  Fetch Price List
 */

function pdp_fetch_pricelists( $salon = false ){
	$google_api = new PDP_Core_Google();
	$client = $google_api->get_client();
	$service = new Google_Service_Sheets( $client );

	$pricelists = $salon ? pdp_get_salon_pricelist_id( $salon ) : pdp_get_salons_pricelist_id();

	foreach( $pricelists as $pricelist ){
		$ranges = [];
		$titles = [];

		$spreadsheet = $service->spreadsheets->get( $pricelist['spreadsheet_id'] );

		foreach( $spreadsheet->getSheets() as $sheet ){
			$ranges[] = $sheet['properties']['title'] . '!A:O';
			$titles[] = rtrim( $sheet['properties']['title'] );
		}

		$response = $service->spreadsheets_values->batchGet( $pricelist['spreadsheet_id'], array( 'ranges' => $ranges ) )->getValueRanges();

		$parsed = pdp_parse_pricelist( $titles, $response );

		if( $parsed ){
			update_post_meta( $pricelist['salon_id'], '_pricelist', $parsed );
			update_post_meta( $pricelist['salon_id'], '_pricelist_last_update', current_time( "Y-m-d H:i:s" ) );
		}
	}
}


/**
 *  Price List Parser
 */

function pdp_parse_pricelist( $categories, $data ){
	$default_language = pdp_get_default_language();

	$parsed_data = [];

	foreach( $data as $key => $range ){
		$available_langs = [];

		$category_names = [];
		$services = [];
		$subcategory_services = [];
		$subcategories = [];
		$subcategory_title = [];

		$is_subcategory = false;
		$is_master_option = false;
		$is_variable_price = false;

		foreach( $range as $row_index => $row ){
			if( isset( $row[0] ) && $row[0] !== '' ){
				$row = array_values( array_filter( $row ) );

				if( $row_index === 0 ){
					foreach( $row as $column_index => $column ){
						strpos( $column, '[lang="' ) !== false ? $available_langs[] = str_replace( ['[lang="', '"]'], '', $column ) : null;
					}
				}
				else if( strpos( $row[0], '[category]' ) !== false ){
					foreach( $available_langs as $available_lang ){
						$category_names[$available_lang] = str_replace( '[category]', '', rtrim( array_shift( $row ) ) );
					}
				}
				else if( $row[0] === '[subcategory-begin]' ){
					$is_subcategory = true;
				}
				else if( $row[0] === '[subcategory-end]' ){
					$subcategories[] = array(
						'name'      => $subcategory_title,
						'services'  => $subcategory_services
					);

					$is_subcategory = false;

					$subcategory_services = [];
				}
				else{
					if( strpos( $row[0], '[subcategory-title]' ) !== false ){
						foreach( $available_langs as $lang_key => $available_lang ){
							$subcategory_title[$available_lang] = str_replace( '[subcategory-title]', '', $row[$lang_key] );
						}
					}
					else{
						$current_service = [];
						$is_pro = boolval( strpos( $row[0], '[pro]' ) );

						foreach( $available_langs as $lang_key => $available_lang ){
							$current_service['name'][$available_lang] = str_replace( '[pro]', '', rtrim( array_shift( $row ) ) );
						}
						
						$current_service['id'] = md5( $categories[$key] . '_' . $current_service['name'][$default_language] );

						switch( count( $row ) ){
							case 1:
								$current_service['master'] = false;
								if( strpos( $row[0], '[from]' ) !== false ){
									$current_service['prices'] = [[str_replace( '[from]', '', $row[0] )]];
									$current_service['variable'] = true;
									$is_variable_price = true;
								}
								else{
									$current_service['prices'] = [$row];
								}
								break;
							case 3:
							case 4:
								$current_service['master'] = false;
								$current_service['prices'] = array_chunk( $row, 1 );
								break;
							case 2:
							case 6:
							case 8:
								$current_service['master'] = true;
								$current_service['prices'] = array_chunk( $row, 2 );
								break;
						}

						if( $current_service['master'] ){
							$is_master_option = true;
						}

						if( !isset( $current_service['variable'] ) ){
							$current_service['variable'] = false;
						}

						$current_service['pro'] = $is_pro;

						$is_subcategory ? $subcategory_services[] = $current_service : $services[] = $current_service;
					}
				}
			}
		}

		$category = array(
			'name'                  => $categories[$key],
			'is_master_option'      => $is_master_option,
			'is_variable_price'     => $is_variable_price
		);

		if(
			$categories[$key] === 'стрижки/укладки/прически' ||
		    $categories[$key] === 'уходы для волос' ||
		    $categories[$key] === 'все виды окрашиваний'
		){
			$category['is_hair_services'] = true;
		}
		else{
			$category['is_hair_services'] = false;
		}

		if( $subcategories ){
			$category['subcategories'] = $subcategories;
		}
		else{
			$category['subcategories'][] = array(
				'name'      => $category_names,
				'services'  => $services
			);
		}

		$parsed_data[pdp_service_slug_to_key( $categories[$key] )] = $category;
	}

	return $parsed_data;
}

function pdp_get_template( $path = '', $data = [] ){
	if( $path && is_string( $path ) ) {
		require( PDP_PLUGIN_PATH . 'templates/' . $path );
	}
}

function pdp_get_salon_recipients( $id ){
	return explode( ',', get_post_meta( $id, '_notification_recipients', true ) );
}

function pdp_get_post_data(){
	$data = array();

	foreach( $_POST as $key => $value ){
		$data[$key] = $value;
	}

	return $data;
}

function pdp_utm_fields(){
	if( isset( $_GET['utm_source'] ) ){
		$utm_values = array(
			'utm_source'    => $_GET['utm_source'],
			'utm_medium'    => $_GET['utm_medium'],
			'utm_campaign'  => $_GET['utm_campaign'],
			'utm_content'   => $_GET['utm_content'],
			'utm_term'      => $_GET['utm_term']
		);

		foreach( $utm_values as $key => $value ){
			echo "<input type='hidden' name='{$key}' value='{$value}'>";
		}
	}
}

if( !function_exists( 'write_log' ) ){
	function write_log( $log ){
		if( true === WP_DEBUG ){
			if( is_array( $log ) || is_object( $log ) ){
				error_log( print_r( $log, true ) );
			}
			else{
				error_log( $log );
			}
		}
	}
}


/**
 *  Getting service categories
 */

function pdp_get_service_categories(){
	$categories = array();
	$categories_raw = carbon_get_theme_option( 'service_categories' );
	$langs = pll_languages_list( ['hide_empty' => false] );

	foreach( $categories_raw as $category ){
		$name = array();

		foreach( $langs as $lang ){
			$name[$lang === 'uk' ? 'ua' : $lang] = $category[$lang === 'uk' ? 'ua' : $lang];
		}

		$categories[] = array(
			'slug'      => pdp_service_slug_to_key( $category['slug'] ),
			'name'      => $name,
			'cover'     => array(
				'1x'        => wp_get_attachment_image_url( $category['cover1x'], 'full' ),
				'2x'        => wp_get_attachment_image_url( $category['cover2x'], 'full' )
			)
		);
	}

	return $categories;
}


/**
 *  Getting salon pricelist
 */

function pdp_get_pricelist( $id ){
	$categories = pdp_get_service_categories();
	$pricelists = get_post_meta( $id instanceof WP_REST_Request ? $id->get_param( 'salon' ) : $id, '_pricelist', true );

	foreach( $pricelists as $key => $pricelist ){
		foreach( $categories as $category ){
			if( $category['slug'] === $pricelist['name'] ){
				$pricelists[$key]['image'] = wp_get_attachment_image( $category['cover'], 'services-slider-thumb' );
			}
		}
	}

	return $pricelists;
}