<?php

class PDP_Core_Shortcodes {
    private $shortcodes;

    public function __construct(){
        $this->shortcodes = array(
            'logos_grid',
            'logos_grid_item',
	        'list_grid',
	        'list_grid_item'
        );

        $this->init();
    }

    private function init(){
        foreach( $this->shortcodes as $shortcode ){
            add_shortcode( $shortcode, array( $this, $shortcode . '_func' ) );
        }
    }

    public function logos_grid_func( $atts, $content = null ){
        return '<div class="logos-grid logos-grid_style-01">'. do_shortcode( $content ) . '</div>';
    }

    public function logos_grid_item_func( $atts, $content = null ){
        $atts = shortcode_atts( array(
            'image'     => '',
            'link'      => ''
        ), $atts );

        if( $atts['image'] ){
            $image_tag = wp_get_attachment_image( $atts['image'], 'full' );

            $html = '<div class="logos-grid__item">';

            if( $atts['link'] ) {
                $html .= "<a href='{$atts['link']}' target='_blank'>{$image_tag}</a>";
            }
            else{
                $html .= "<a>{$image_tag}</a>";
            }

            $html .= '</div>';

            return $html;
        }
        else{
            return false;
        }
    }

    public function list_grid_func( $atts, $content = null ){
	    $atts = shortcode_atts( array(
		    'cols' => 3
	    ), $atts );

	    return "<ol class='list-grid list-grid_ordered' style='grid-template-columns: repeat({$atts['cols']}, 1fr)'>" . do_shortcode( $content ) . "</ol>";
    }

	public function list_grid_item_func( $atts, $content = null ){
		return "<li class='list-item'><div>{$content}</div></li>";
	}
}