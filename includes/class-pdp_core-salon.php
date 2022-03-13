<?php

class PDP_Core_Salon {
	public static function get_all( WP_REST_Request $request ){
        $data = [];

        $salons = get_posts(
            array(
                'numberposts'   => -1,
                'post_type'     => 'salon',
                'order'         => 'ASC',
	            'lang'          => $request->get_param( 'lang' )
            )
        );

        foreach( $salons as $salon ){
        	if( carbon_get_post_meta( $salon->ID, 'display_in_booking' ) === 'yes' ){
		        $terms = get_the_terms( $salon->ID, 'city' );
		        $city = array_pop( $terms )->name;

		        $has_pricelist = false;

		        if( carbon_get_post_meta( $salon->ID, 'pricelist_sheet_id' ) ){
			        $has_pricelist = true;
		        }

		        $data[] = array(
			        'id'        => $salon->ID,
			        'city'      => $city,
			        'title'     => $salon->post_title,
			        'email'     => carbon_get_post_meta( $salon->ID, 'email' ),
			        'phone'     => carbon_get_post_meta( $salon->ID, 'phone' ),
			        'pricelist' => $has_pricelist
		        );
	        }
        }

        return $data;
    }

    public static function get_by_id( $id ){
    	return get_post( $id );
	}

    public static function get_pricelist( WP_REST_Request $request ){
        $categories = carbon_get_theme_option( 'service_categories' );
        $pricelists = get_post_meta( $request->get_param( 'salon' ), '_pricelist', true );

        foreach( $pricelists as $key => $pricelist ){
            foreach( $categories as $category ){
                if( $category['slug'] == $pricelist['name'] ){
                    $pricelists[$key]['image'] = wp_get_attachment_image( $category['cover'], 'services-slider-thumb' );
                }
            }
        }

        return $pricelists;
    }

    public static function get_categories(){
        $data = [];
        $categories_raw = carbon_get_theme_option( 'service_categories' );

        foreach( $categories_raw as $category ){
            $data[] = array(
	            'name'     => array(
	            	'ru'    => $category['title'],
		            'ua'    => $category['title_ua']
	            ),
                'img'       => wp_get_attachment_image( $category['cover'], 'services-slider-thumb' ),
                'slug'      => pdp_service_slug_to_key( $category['slug'] )
            );
        }

        return $data;
    }
}