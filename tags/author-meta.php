<?php
/**
 * Author Meta Dynamic Tag.
 *
 * @package BPF_Widgets
 * @since 1.0.0
 */

namespace BPF_Dynamic_Tag\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Author_Info_Meta.
 *
 * Dynamic tag for displaying the author meta.
 *
 * @since 1.0.0
 */
class Author_Info_Meta extends Tag {

	/**
	 * Get tag name.
	 *
	 * Retrieve the dynamic tag name for internal use.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Tag name.
	 */
	public function get_name() {
		return 'author-info-meta';
	}

	/**
	 * Get tag title.
	 *
	 * Retrieve the dynamic tag title displayed in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Tag title.
	 */
	public function get_title() {
		return esc_html__( 'Author Info', 'bpf-widget' );
	}

	/**
	 * Get tag group.
	 *
	 * Retrieve the group the tag belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Dynamic tag group.
	 */
	public function get_group() {
		return 'author';
	}

	/**
	 * Get tag categories.
	 *
	 * Retrieve the list of categories the tag belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Dynamic tag categories.
	 */
	public function get_categories() {
		return [
			TagsModule::NUMBER_CATEGORY,
			TagsModule::TEXT_CATEGORY,
			TagsModule::URL_CATEGORY,
			TagsModule::POST_META_CATEGORY,
			TagsModule::COLOR_CATEGORY,
		];
	}

	/**
	 * Get the key for the panel template setting.
	 *
	 * This method returns the setting key used by Elementor's panel to identify the
	 * dynamic tag control. In this case, it returns 'key', which corresponds to the
	 * field control that selects the type of author information (ID, bio, etc.) to display.
	 *
	 * @return string The setting key for the dynamic tag control.
	 */
	public function get_panel_template_setting_key() {
		return 'key';
	}

	/**
	 * Register controls.
	 *
	 * Define the controls for the dynamic tag.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label'   => esc_html__( 'Field', 'bpf-widget' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'display_name',
				'options' => [
					'display_name' => esc_html__( 'Display Name', 'bpf-widget' ),
					'ID'           => esc_html__( 'ID', 'bpf-widget' ),
					'description'  => esc_html__( 'Bio', 'bpf-widget' ),
					'email'        => esc_html__( 'Email', 'bpf-widget' ),
					'url'          => esc_html__( 'Website', 'bpf-widget' ),
					'profile_url'  => esc_html__( 'Profile URL', 'bpf-widget' ),
					'meta'         => esc_html__( 'Author Meta', 'bpf-widget' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label'     => esc_html__( 'Meta Key', 'bpf-widget' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'key' => 'meta',
				],
			]
		);
	}

	/**
	 * Render dynamic tag output.
	 *
	 * Generates the HTML output for the author meta, with optional trimming based on length control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {
		$key      = $this->get_settings( 'key' );
		$meta_key = $this->get_settings( 'meta_key' );

		if ( empty( $key ) && empty( $meta_key ) ) {
			return;
		}

		if ( 'profile_url' === $key ) {
			$value = get_author_posts_url( get_the_author_meta( 'ID' ) );
		} elseif ( 'meta' === $key && ! empty( $meta_key ) ) {
			$value = get_the_author_meta( $meta_key );
		} elseif ( 'ID' === $key ) {
			$value = is_author() ? get_queried_object_id() : get_the_author_meta( 'ID' );
		} else {
			$value = get_the_author_meta( $key );
		}

		echo wp_kses_post( $value );
	}
}
