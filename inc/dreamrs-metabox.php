<?php
function dreamrs_portfolio_metabox( $meta_boxes ) {
	$dreamrs_prefix = '_dreamrs_';
	$meta_boxes[] = array(
		'id'        => 'portfolio_single_metaboxs',
		'title'     => esc_html__( 'Portfolio Single Metabox', 'dreamrs' ),
		'post_types'=> array( 'portfolio' ),
		'context'   => 'side',
		'priority'  => 'high',
		'autosave'  => 'false',
		'fields'    => array(
			array(
				'id'    => $dreamrs_prefix . 'project_short_info',
				'type'  => 'text',
				'name'  => esc_html__( 'Project Short Info', 'dreamrs' ),
				'placeholder' => esc_html__( 'Project Short Info', 'dreamrs' ),
			),
			array(
				'name'    => esc_html__( 'Project Image Size', 'dreamrs' ),
				'id'      => $dreamrs_prefix . 'portfolio_img_size',
				'type'    => 'select',
				'options' => array(
					'0' => 'Select Size',
					'1' => 'Image Size [555x589]',
					'2' => 'Image Size [458x491]'
				),
				'inline' => true,
			),
		),
	);

	$meta_boxes[] = array(
		'id'        => 'apartment_single_metaboxes',
		'title'     => esc_html__( 'Apartment Metaboxes', 'dreamrs' ),
		'post_types'=> array( 'apartment' ),
		'context'   => 'side',
		'priority'  => 'high',
		'autosave'  => 'false',
		'fields'    => array(
			array(
				'id'    => $dreamrs_prefix . 'apartment_short_info',
				'type'  => 'text',
				'name'  => esc_html__( 'Apartment Short Info', 'dreamrs' ),
				'placeholder' => esc_html__( 'Apartment Short Info', 'dreamrs' ),
			),
			array(
				'id'    => $dreamrs_prefix . 'apartment_baths',
				'type'  => 'number',
				'name'  => esc_html__( 'Apartment Baths', 'dreamrs' ),
				'placeholder' => esc_html__( 'Ex: 3', 'dreamrs' ),
				'min'  => 0,
				'step' => 1,
			),
			array(
				'id'    => $dreamrs_prefix . 'apartment_beds',
				'type'  => 'number',
				'name'  => esc_html__( 'Apartment Beds', 'dreamrs' ),
				'placeholder' => esc_html__( 'Ex: 3', 'dreamrs' ),
				'min'  => 0,
				'step' => 1,
			),
			array(
				'id'    => $dreamrs_prefix . 'apartment_size',
				'type'  => 'number',
				'name'  => esc_html__( 'Apartment Size', 'dreamrs' ),
				'placeholder' => esc_html__( 'Ex: 2400', 'dreamrs' ),
				'min'  => 0,
				'step' => 1,
			),
		),
	);


	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'dreamrs_portfolio_metabox' );