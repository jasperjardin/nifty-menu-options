<?php
/**
 * The class that handles WordPress frontend hooks and settings.
 *
 * (c) Dunhakdis <dunhakdis@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.4
 *
 * @category NiftyMenuOptions\Public
 * @package  NiftyMenuOptions
 * @author   Dunhakdis Software Creatives <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @version  GIT:github.com/jasperjardin/nifty-menu-options
 * @link     https://github.com/jasperjardin/nifty-menu-options
 * @since    1.0.0
 */

namespace DSC\NiftyMenuOptions;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * The class that handles the WordPress frontend hooks and settings.
 *
 * @category NiftyMenuOptions\Public
 * @package  NiftyMenuOptions
 * @author   Dunhakdis Software Creatives <emailnotdisplayed@domain.tld>
 * @author   Jasper J. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @link     https://github.com/jasperjardin/nifty-menu-options
 * @since    1.0.0
 */
final class PublicPages {

	/**
	 * The loader is the one who regesters the handles the hooks of the plugin
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $loader    Handles and registers all
	 *                                         hooks for the plugin.
	 */
	private static $loader;

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $name    The ID of this plugin.
	 */
	private static $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	private static $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string  $name    The name of the plugin.
	 * @param integer $version The version of this plugin.
	 * @param string  $loader  The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct( $name, $version, $loader ) {

		self::$loader  = $loader;
		self::$name    = $name;
		self::$version = $version;
		add_filter( 'nav_menu_item_title', array( $this, 'display_menu_icons' ), 10, 4 );
	}

	/**
	 * Gets the $name class property.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string $name Return the name of the plugin
	 */
	public static function get_name() {
		return self::$name;
	}

	/**
	 * Gets the $version class property.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string $version Return the version of the plugin
	 */
	public static function get_version() {
		return self::$version;
	}

	/**
	 * This method enqueue the CSS filess for the frontend of the plugin.
	 *
	 * @param int $id The ID of the menu item.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function set_enqueue_styles( $id ) {
		$name            = self::get_name();
		$version         = self::get_version();
		$plugin_dir_url  = plugin_dir_url( dirname( __FILE__ ) );
		$icon_stylesheet = plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/icon-stylesheet/';

		$get_menu_icon = Metabox::get_menu_icon( $id );

		$enqueued_icon_libraries = apply_filters(
			'nifty_enqueued_icon_libraries',
			array(
				'nifty-material-icon' => array(
					'id'       => 'nifty-material-icon',
					'src'      => 'material-icon.css',
					'enqueued' => true,
				),
			)
		);

		if ( ! empty( $enqueued_icon_libraries ) ) {

			wp_enqueue_style(
				$name,
				$plugin_dir_url . 'public/css/nifty-menu-options.css',
				array(),
				$version,
				'all'
			);

			foreach ( $enqueued_icon_libraries as $enqueued_icon_library ) {

				if ( empty( $enqueued_icon_library['version'] ) ) {
					$enqueued_icon_library['version'] = $version;
				}

				if ( empty( $enqueued_icon_library['dependencies'] ) ) {
					$enqueued_icon_library['dependencies'] = array();
				}

				if ( empty( $enqueued_icon_library['media'] ) ) {
					$enqueued_icon_library['media'] = 'all';
				}

				if ( $enqueued_icon_library['enqueued'] && empty( $enqueued_icon_library['external_src'] ) ) {
					wp_enqueue_style(
						$enqueued_icon_library['id'],
						$icon_stylesheet . $enqueued_icon_library['src'],
						$enqueued_icon_library['dependencies'],
						$enqueued_icon_library['version'],
						$enqueued_icon_library['media']
					);
				}

				if ( ! empty( $enqueued_icon_library['external_src'] ) ) {
					wp_enqueue_style(
						$enqueued_icon_library['id'],
						$enqueued_icon_library['external_src'],
						$enqueued_icon_library['dependencies'],
						$enqueued_icon_library['version'],
						$enqueued_icon_library['media']
					);
				}
			}
		}
	}

	/**
	 * This method enqueue the JS files for the frontend of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function set_enqueue_scripts() {
	}

	/**
	 * This method enqueue theLocalization Scripts for the frontend of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function set_localize_scripts() {
	}

	/**
	 * This method displays the menu icons.
	 *
	 * @param string   $title The menu item's title.
	 * @param WP_Post  $item  The current menu item.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int      $depth Depth of menu item. Used for padding.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string $title  Menu item title.
	 */
	public static function display_menu_icons( $title, $item, $args, $depth ) {
		$style                 = '';
		$css                   = array(
			'icon_color'    => '',
			'icon_position' => '',
			'icon_size'     => '',
		);
		$get_icon              = Metabox::get_menu_icon( $item->ID );
		$get_icon_color        = Metabox::get_menu_icon_color( $item->ID );
		$get_icon_position     = Metabox::get_menu_icon_position( $item->ID );
		$get_icon_position_css = $get_icon_position['css'];
		$get_icon_size         = Metabox::get_menu_icon_size( $item->ID );
		$get_icon_size_css     = $get_icon_size['css'];

		self::set_enqueue_styles( $item->ID );

		if ( ! empty( $get_icon_color ) ) {
			$css['icon_color'] = 'color:' . esc_attr( $get_icon_color ) . '; ';
		}
		if ( ! empty( $get_icon_position_css ) ) {
			$css['icon_position'] = esc_attr( $get_icon_position_css );
		}

		if ( ! empty( $get_icon_size_css ) ) {
			$css['icon_size'] = 'font-size:' . esc_attr( $get_icon_size_css ) . '; ';
		}

		if ( ! empty( $get_icon ) ) {
			$style = 'style="' . esc_attr( $css['icon_color'] . $css['icon_position'] . $css['icon_size'] ) . '"';

			$args->link_before = '<i class="material-icons nifty-displayed-icon" ' . $style . '>' . esc_html( $get_icon ) . '</i>';
		} else {
			$args->link_before = '';
		}

		return $title;
	}
}
