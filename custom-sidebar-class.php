<?php

/**
 * Nivoshop Custom Sidebar
 * Custom sidebar builder php class
 *
 * @author   Nivo Themes
 * @since    1.0
 * @package  nivoshop
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class CustomSidebars {
	
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'nivoshop_custom_widgets_init' ) );
	}

	public function nivoshop_custom_widgets_init() {
		
		$sidebared_pages = get_pages();
		$sidebared_products = get_posts('post_type=product&posts_per_page=-1');
		
		if ( !empty($sidebared_products) ) {			
			$sidebared_content = array_merge( $sidebared_pages, $sidebared_products );
		} else {
			$sidebared_content = $sidebared_pages;
		}
		
		foreach ( $sidebared_content as $sp ) {
			
			$page_id = $sp->ID;
			$page_title = $sp->post_title;
			$page_name = $sp->post_name;
			$_showsidebar = get_post_meta($sp->ID, '_showsidebar', true);
			
			if ( !empty($_showsidebar) && ( $_showsidebar == 'show' ) ) :
			
				$sidebar_args['sidebar-'.$page_id] = array(
					'name'          => $page_title,
					'id'            => 'sidebar-'.$page_id,
					'description'   => $page_title . __(' sayfası bileşen alanı', 'nivoshop'),
				);
				
				$sidebar_args = apply_filters( 'nivoshop_sidebar_args', $sidebar_args );

				foreach ( $sidebar_args as $sidebar => $args ) {
					$widget_tags = array(
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h3 class="widget-title">',
						'after_title'   => '</h3>',
					);

					/**
					 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
					 *
					 * 'nivoshop_customsidebar_widget_tags'
					 */
					$filter_hook = sprintf( 'nivoshop_%s_widget_tags', $sidebar );
					$widget_tags = apply_filters( $filter_hook, $widget_tags );

					if ( is_array( $widget_tags ) ) {
						register_sidebar( $args + $widget_tags );
					}
				}
				
			endif;
		}
		
		wp_reset_postdata();
	}	
}
new CustomSidebars;
