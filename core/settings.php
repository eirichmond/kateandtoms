<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( ! class_exists( 'KTSite_Settings' ) ) :
	class KTSite_Settings {

		private $settings_api;

		function __construct() {
			$this->settings_api = new WeDevs_Settings_API();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		function admin_init() {

			// set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			// initialize settings
			$this->settings_api->admin_init();
		}

		function admin_menu() {
			add_options_page( 'Site Settings', 'Site Settings', 'delete_posts', 'settings_api_test', array( $this, 'plugin_page' ) );
		}

		function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'kat_main',
					'title' => __( 'Main Settings', 'kat_settings' ),
				),
				array(
					'id'    => 'kat_search',
					'title' => __( 'Search Page', 'kat_settings' ),
				),
			/*
			array(
				'id'    => 'kat_basics',
				'title' => __( 'Basic Settings', 'kat_settings' )
			),
			array(
				'id'    => 'kat_advanced',
				'title' => __( 'Advanced Settings', 'kat_settings' )
			)
			*/
			);
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {
			$allhouses       = $this->get_custom_type_houses();
			$settings_fields = array(
				'kat_main'     => array(
					array(
						'name' => 'html',
						'desc' => __( '@TODO ER consolidate all other site settings here to keep theme all in one place', 'kat_settings' ),
						'type' => 'html',
					),
				),
				'kat_search'   => array(
					array(
						'name' => 'html',
						'desc' => __( '<h3>Top section of Search All page</h3>', 'kat_settings' ),
						'type' => 'html',
					),
					array(
						'name'              => 'top_section_title',
						'label'             => __( 'Title', 'kat_settings' ),
						'desc'              => __( 'Leading Title of top section', 'kat_settings' ),
						'type'              => 'text',
						'default'           => 'Title',
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'name'    => 'search_all_topsection_house_one',
						'label'   => __( 'House #1', 'kat_settings' ),
						'desc'    => __( 'Select a house to show', 'kat_settings' ),
						'type'    => 'select',
						'default' => 'no',
						'options' => $allhouses,
					),
					array(
						'name'    => 'search_all_topsection_house_two',
						'label'   => __( 'House #2', 'kat_settings' ),
						'desc'    => __( 'Select a house to show', 'kat_settings' ),
						'type'    => 'select',
						'default' => 'no',
						'options' => $allhouses,
					),
					array(
						'name'    => 'search_all_topsection_house_three',
						'label'   => __( 'House #3', 'kat_settings' ),
						'desc'    => __( 'Select a house to show', 'kat_settings' ),
						'type'    => 'select',
						'default' => 'no',
						'options' => $allhouses,
					),
					array(
						'name'    => 'search_all_topsection_house_four',
						'label'   => __( 'House #4', 'kat_settings' ),
						'desc'    => __( 'Select a house to show', 'kat_settings' ),
						'type'    => 'select',
						'default' => 'no',
						'options' => $allhouses,
					),
				),
				'kat_basics'   => array(
					array(
						'name'              => 'text_val',
						'label'             => __( 'Text Input', 'kat_settings' ),
						'desc'              => __( 'Text input description', 'kat_settings' ),
						'placeholder'       => __( 'Text Input placeholder', 'kat_settings' ),
						'type'              => 'text',
						'default'           => 'Title',
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'name'              => 'number_input',
						'label'             => __( 'Number Input', 'kat_settings' ),
						'desc'              => __( 'Number field with validation callback `floatval`', 'kat_settings' ),
						'placeholder'       => __( '1.99', 'kat_settings' ),
						'min'               => 0,
						'max'               => 100,
						'step'              => '0.01',
						'type'              => 'number',
						'default'           => 'Title',
						'sanitize_callback' => 'floatval',
					),
					array(
						'name'        => 'textarea',
						'label'       => __( 'Textarea Input', 'kat_settings' ),
						'desc'        => __( 'Textarea description', 'kat_settings' ),
						'placeholder' => __( 'Textarea placeholder', 'kat_settings' ),
						'type'        => 'textarea',
					),
					array(
						'name' => 'html',
						'desc' => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'kat_settings' ),
						'type' => 'html',
					),
					array(
						'name'  => 'checkbox',
						'label' => __( 'Checkbox', 'kat_settings' ),
						'desc'  => __( 'Checkbox Label', 'kat_settings' ),
						'type'  => 'checkbox',
					),
					array(
						'name'    => 'radio',
						'label'   => __( 'Radio Button', 'kat_settings' ),
						'desc'    => __( 'A radio button', 'kat_settings' ),
						'type'    => 'radio',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
					array(
						'name'    => 'selectbox',
						'label'   => __( 'A Dropdown', 'kat_settings' ),
						'desc'    => __( 'Dropdown description', 'kat_settings' ),
						'type'    => 'select',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
					array(
						'name'    => 'password',
						'label'   => __( 'Password', 'kat_settings' ),
						'desc'    => __( 'Password description', 'kat_settings' ),
						'type'    => 'password',
						'default' => '',
					),
					array(
						'name'    => 'file',
						'label'   => __( 'File', 'kat_settings' ),
						'desc'    => __( 'File description', 'kat_settings' ),
						'type'    => 'file',
						'default' => '',
						'options' => array(
							'button_label' => 'Choose Image',
						),
					),
				),
				'kat_advanced' => array(
					array(
						'name'    => 'color',
						'label'   => __( 'Color', 'kat_settings' ),
						'desc'    => __( 'Color description', 'kat_settings' ),
						'type'    => 'color',
						'default' => '',
					),
					array(
						'name'    => 'password',
						'label'   => __( 'Password', 'kat_settings' ),
						'desc'    => __( 'Password description', 'kat_settings' ),
						'type'    => 'password',
						'default' => '',
					),
					array(
						'name'    => 'wysiwyg',
						'label'   => __( 'Advanced Editor', 'kat_settings' ),
						'desc'    => __( 'WP_Editor description', 'kat_settings' ),
						'type'    => 'wysiwyg',
						'default' => '',
					),
					array(
						'name'    => 'multicheck',
						'label'   => __( 'Multile checkbox', 'kat_settings' ),
						'desc'    => __( 'Multi checkbox description', 'kat_settings' ),
						'type'    => 'multicheck',
						'default' => array(
							'one'  => 'one',
							'four' => 'four',
						),
						'options' => array(
							'one'   => 'One',
							'two'   => 'Two',
							'three' => 'Three',
							'four'  => 'Four',
						),
					),
				),
			);

			return $settings_fields;
		}

		function plugin_page() {
			echo '<div class="wrap">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		function get_pages() {
			$pages         = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}

		function get_custom_type_houses() {

			global $wpdb;
			$tablename = $wpdb->prefix;
			$sql       = $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = %s ORDER BY post_title ASC", 'houses' );
			$results   = $wpdb->get_results( $sql, OBJECT );

			$houses = array();
			foreach ( $results as $house ) {
				$houses[ $house->ID ] = $house->post_title;
			}

			return $houses;
		}
	}
endif;
