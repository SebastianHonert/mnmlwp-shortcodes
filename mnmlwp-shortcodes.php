<?php

/*
Plugin Name: mnmlWP Basic Shortcodes
Plugin URI: https://minimalwordpress.com
Description: This plugin provides the basic shortcodes for the mnmlWP Theme.
Author: Sebastian Honert
Version: 0.2.5
Author URI: https://sebastianhonert.com
Text Domain: mnmlwp-shortcodes
License: GNU General Public License v2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
*/

class MNMLWP_Shortcodes
{
    function __construct()
    {
        add_action('after_setup_theme', array( $this, 'mnmlwp_fix_wpautop' ));
        add_action('after_setup_theme', array( $this, 'mnmlwp_i18n' ));
        add_action('wp_enqueue_scripts', array( $this, 'load_scripts_and_styles' ));
        add_action('init', array( $this, 'mnmlwp_shortcodes' ));
    }
    
    function mnmlwp_fix_wpautop()
    {
        function mnmlwp_fix_shortcodes($content)
        {
            $array = array (
                '<p>[' => '[',
                ']</p>' => ']',
                ']<br />' => ']'
            );
            
            $content = strtr($content, $array);

            return $content;
        }
        
        add_filter('the_content', 'mnmlwp_fix_shortcodes', 10, 1);
    }    

    function mnmlwp_i18n()
    {
        load_plugin_textdomain( 'mnmlwp-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/'  );
    }

    function load_scripts_and_styles()
    {
        // Scripts
        wp_enqueue_script( 'lightbox', plugins_url('assets/js/lightbox2-master/dist/js/lightbox.min.js', __FILE__ ), array('jquery'), '2.9.0', true);
        wp_enqueue_script('inview', plugins_url( 'assets/js/jquery.inview.js', __FILE__ ), array('jquery'), '0.0.1', true);
        wp_enqueue_script('mnmlwp-shortcodes', plugins_url( 'assets/js/mnmlwp-shortcodes.js', __FILE__ ), array('jquery'), '0.0.1', true);

        // Styles
        wp_enqueue_style( 'lightbox', plugins_url('assets/js/lightbox2-master/dist/css/lightbox.min.css', __FILE__ ));
    }

    function mnmlwp_shortcodes()
    {
        // Site URL
        if( ! function_exists('mnmlwp_shortcode_site_url') )
        {
            function mnmlwp_shortcode_site_url() {
                return site_url();
            }
        }

        add_shortcode( 'site_url', 'mnmlwp_shortcode_site_url' );
        
        // Home URL
        if( ! function_exists('mnmlwp_shortcode_home_url') )
        {
            function mnmlwp_shortcode_home_url() {
                return esc_url( home_url() );
            }
        }

        add_shortcode( 'home_url', 'mnmlwp_shortcode_home_url' );

        // Register URL
        if( ! function_exists('mnmlwp_shortcode_register_url') )
        {
            function mnmlwp_shortcode_register_url() {
                return wp_registration_url();
            }
        }

        add_shortcode( 'register_url', 'mnmlwp_shortcode_register_url' );

        // Login URL
        if( ! function_exists('mnmlwp_shortcode_login_url') )
        {
            function mnmlwp_shortcode_login_url() {
                return wp_login_url( esc_url( add_query_arg( 'welcome', 1, esc_url( home_url() ) ) ) );
            }
        }

        add_shortcode( 'login_url', 'mnmlwp_shortcode_login_url' );

        // User name
        if( ! function_exists('mnmlwp_shortcode_user_name') )
        {
            function mnmlwp_shortcode_user_name() {
                return is_user_logged_in() ? wp_get_current_user()->user_login : esc_html__('Anonymous', 'mnmlwp-shortcodes');
            }
        }

        add_shortcode( 'user_name', 'mnmlwp_shortcode_user_name' );

        // Private content (registered users only)
        if( ! function_exists('mnmlwp_shortcode_private_content') )
        {
            function mnmlwp_shortcode_private_content( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'message' => '',
                ), $atts ) );

                if( ! is_user_logged_in() )
                    return $message ? $message : false;

                return do_shortcode( $content );
            }
        }

        add_shortcode( 'private', 'mnmlwp_shortcode_private_content' );

        // Flex columns (wrapper)
        if( ! function_exists('mnmlwp_shortcode_flex_columns') )
        {
            function mnmlwp_shortcode_flex_columns( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="mnmlwp-flex-columns ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'mnmlwp_flex_columns', 'mnmlwp_shortcode_flex_columns' );

        // Flex column half
        if( ! function_exists('mnmlwp_shortcode_flex_column') )
        {
            function mnmlwp_shortcode_flex_column( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                    'size' => ''
                ), $atts ) );

                $allowed = array(
                    'half',
                    'third',
                    'two-third',
                    'fourth',
                    'three-fourth',
                    'fifth',
                    'two-fifth',
                    'three-fifth'
                );

                if( ! in_array( $size, $allowed ) ) {
                    return __('Please select one of the available column sizes: one_half, one_third, two_third, one_fourth, three_fourth, one_fifth, two_fifth, three_fifth.', 'mnmlwp');
                }

                return '<div class="mnmlwp-flex-column mnmlwp-flex-column--' . $size . ' ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'mnmlwp_flex_column', 'mnmlwp_shortcode_flex_column' );

        // Row
        if( ! function_exists('mnmlwp_shortcode_row') )
        {
            function mnmlwp_shortcode_row( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="mnmlwp-row ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'row', 'mnmlwp_shortcode_row' );

        // Column
        if( ! function_exists('mnmlwp_shortcode_column') )
        {
            function mnmlwp_shortcode_column( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="mnmlwp-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'column', 'mnmlwp_shortcode_column' );

        // Clear
        if( ! function_exists('mnmlwp_shortcode_clear_column') )
        {
            function mnmlwp_shortcode_clear_column( $atts )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="clear-column ' . $class . '" style="' . $style . '"></div>';
            }
        }

        add_shortcode( 'clear_column', 'mnmlwp_shortcode_clear_column' );
        add_shortcode( 'clear_columns', 'mnmlwp_shortcode_clear_column' );

        // Clearfix columns
        if( ! function_exists('mnmlwp_shortcode_columns_clearfix') )
        {
            function mnmlwp_shortcode_columns_clearfix( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="mnmlwp-columns ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'mnmlwp_columns', 'mnmlwp_shortcode_columns_clearfix' );

        // Full Width Column
        if( ! function_exists('mnmlwp_shortcode_column_full_width') )
        {
            function mnmlwp_shortcode_column_full_width( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="full-width ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'full_width', 'mnmlwp_shortcode_column_full_width' );

        // One Half Column
        if( ! function_exists('mnmlwp_shortcode_column_one_half') )
        {
            function mnmlwp_shortcode_column_one_half( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                $style = ! empty( $style ) ? ' style="' . $style  . '"' : '';

                return '<div class="one-half ' . $class . '"' . $style . '>' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_half', 'mnmlwp_shortcode_column_one_half' );

        // One Half Last Column
        if( ! function_exists('mnmlwp_shortcode_column_one_half_last') )
        {
            function mnmlwp_shortcode_column_one_half_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                $style = ! empty( $style ) ? ' style="' . $style  . '"' : '';

                return '<div class="one-half last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_half_last', 'mnmlwp_shortcode_column_one_half_last' );

        // One Third Column
        if( ! function_exists('mnmlwp_shortcode_column_one_third') )
        {
            function mnmlwp_shortcode_column_one_third( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="one-third ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_third', 'mnmlwp_shortcode_column_one_third' );

        // One Third Last Column
        if( ! function_exists('mnmlwp_shortcode_column_one_third_last') )
        {
            function mnmlwp_shortcode_column_one_third_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="one-third last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_third_last', 'mnmlwp_shortcode_column_one_third_last' );

        // Two Third Column
        if( ! function_exists('mnmlwp_shortcode_column_two_third') )
        {
            function mnmlwp_shortcode_column_two_third( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="two-third ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'two_third', 'mnmlwp_shortcode_column_two_third' );

        // Two Third Last Column
        if( ! function_exists('mnmlwp_shortcode_column_two_third_last') )
        {
            function mnmlwp_shortcode_column_two_third_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="two-third last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'two_third_last', 'mnmlwp_shortcode_column_two_third_last' );

        // One Fourth Column
        if( ! function_exists('mnmlwp_shortcode_column_one_fourth') )
        {
            function mnmlwp_shortcode_column_one_fourth( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="one-fourth ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_fourth', 'mnmlwp_shortcode_column_one_fourth' );

        // One Fourth Last Column
        if( ! function_exists('mnmlwp_shortcode_column_one_fourth_last') )
        {
            function mnmlwp_shortcode_column_one_fourth_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="one-fourth last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_fourth_last', 'mnmlwp_shortcode_column_one_fourth_last' );

        // Three Fourth Column
        if( ! function_exists('mnmlwp_shortcode_column_three_fourth') )
        {
            function mnmlwp_shortcode_column_three_fourth( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="three-fourth ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'three_fourth', 'mnmlwp_shortcode_column_three_fourth' );

        // Three Fourth Last Column
        if( ! function_exists('mnmlwp_shortcode_column_three_fourth_last') )
        {
            function mnmlwp_shortcode_column_three_fourth_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="three-fourth last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'three_fourth_last', 'mnmlwp_shortcode_column_three_fourth_last' );

        // One Fifth Column
        if( ! function_exists('mnmlwp_shortcode_column_one_fifth') )
        {
            function mnmlwp_shortcode_column_one_fifth( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="one-fifth ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_fifth', 'mnmlwp_shortcode_column_one_fifth' );

        // One Fifth Last Column
        if( ! function_exists('mnmlwp_shortcode_column_one_fifth_last') )
        {
            function mnmlwp_shortcode_column_one_fifth_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="one-fifth last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'one_fifth_last', 'mnmlwp_shortcode_column_one_fifth_last' );
        
        // Two Fifth Column
        if( ! function_exists('mnmlwp_shortcode_column_two_fifth') )
        {
            function mnmlwp_shortcode_column_two_fifth( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="two-fifth ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'two_fifth', 'mnmlwp_shortcode_column_two_fifth' );

        // Two Fifth Last Column
        if( ! function_exists('mnmlwp_shortcode_column_two_fifth_last') )
        {
            function mnmlwp_shortcode_column_two_fifth_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="two-fifth last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'two_fifth_last', 'mnmlwp_shortcode_column_two_fifth_last' );
        
        // Three Fifth Column
        if( ! function_exists('mnmlwp_shortcode_column_three_fifth') )
        {
            function mnmlwp_shortcode_column_three_fifth( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="three-fifth ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'three_fifth', 'mnmlwp_shortcode_column_three_fifth' );

        // Three Fifth Last Column
        if( ! function_exists('mnmlwp_shortcode_column_three_fifth_last') )
        {
            function mnmlwp_shortcode_column_three_fifth_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="three-fifth last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'three_fifth_last', 'mnmlwp_shortcode_column_three_fifth_last' );
        
        // Three Fifth Column
        if( ! function_exists('mnmlwp_shortcode_column_four_fifth') )
        {
            function mnmlwp_shortcode_column_four_fifth( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="four-fifth ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'four_fifth', 'mnmlwp_shortcode_column_four_fifth' );

        // Three Fifth Last Column
        if( ! function_exists('mnmlwp_shortcode_column_four_fifth_last') )
        {
            function mnmlwp_shortcode_column_four_fifth_last( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="four-fifth last-column ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'four_fifth_last', 'mnmlwp_shortcode_column_four_fifth_last' );

        // Valuemeters Wrapper
        if( ! function_exists('mnmlwp_shortcode_valuemeters') )
        {
            function mnmlwp_shortcode_valuemeters( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<div class="mnmlwp-valuemeters ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';
            }
        }

        add_shortcode( 'valuemeters', 'mnmlwp_shortcode_valuemeters' );

        // Valuemeter
        if( ! function_exists('mnmlwp_shortcode_valuemeter') )
        {
            function mnmlwp_shortcode_valuemeter( $atts )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                    'name' => '',
                    'value' => '',
                    'color' => '',
                    'background' => '',
                ), $atts ) );

                $itemNameStyle = $color ? 'color:' . $color : '';
                $itemValueStyle = $background ? 'background:' . $background : '';

                if( empty( $value ) || empty( $name ) )
                    return '<p>' . esc_html__('Valuemeter items require a name and value attribute.', 'mnmlwp-shortcodes') . '</p>';

                return '<div class="mnmlwp-valuemeter ' . $class . '" style="' . $style . '"><span class="mnmlwp-valuemeter-item-value" data-value="' . $value . '" style="' . $itemValueStyle . '"></span><span class="mnmlwp-valuemeter-item-name" style="' . $itemNameStyle . '">' . $name . '</span></div>';
            }
        }

        add_shortcode( 'valuemeter', 'mnmlwp_shortcode_valuemeter' );

        // Blockquote
        if( ! function_exists ( 'mnmlwp_shortcode_blockquote' ) )
        {
            function mnmlwp_shortcode_blockquote( $atts, $content = null)
            {
                extract( shortcode_atts( array(
                    'author' => '',
                    'src' => '',
                    'url' => '',
                    'date' => '',
                ), $atts ) );

                $html = '<blockquote>';
                $html .=  '<p>' . $content . '</p>';

                if( $author || $src || $url )
                {
                    $html .= '<p class="blockquote-cite"><cite>';

                        if( $author ) {
                            $html .= $author;

                            if( $src || $date || $url )
                                $html .= ', ';
                        }

                        if( $src ) {
                            $html .= $src;

                            if( $date || $url )
                                $html .= ', ';
                        }

                        if( $date ) {
                            $html .= $date;

                            if( $url )
                                $html .= ', ';
                        }

                        if( $url )
                            $html .= '<a href="' . $url . '" target="_blank">' . esc_html__('URL', 'mnmlwp-shortcodes') . '</a>';

                    $html .= '</cite></p>';
                }

                $html .=  '</blockquote>';

                return $html;
            }
        }

        add_shortcode( 'blockquote', 'mnmlwp_shortcode_blockquote' );

        // Message boxes
        if( ! function_exists ( 'mnmlwp_shortcode_msg_box' ) )
        {
            function mnmlwp_shortcode_msg_box( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'class' => '',
                    'style' => '',
                    'type'  => '',
                ), $atts ) );

                if( ! $type )
                    $type = 'mnmlwp-msg-default';
                    
                $types_arr = explode( ' ', $type );
                
                foreach( $types_arr as $key => $t )
                {
                    if( ! stristr($t, 'mnmlwp-msg-') ) {
                        $types_arr[$key] = 'mnmlwp-msg-' . $t;
                    }
                }
                
                $types = implode(' ', $types_arr);
                
                $html = '<div class="mnmlwp-msg ' . $types . ' ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';

                return $html;
            }
        }

        add_shortcode ( 'msg', 'mnmlwp_shortcode_msg_box' );

        // Button Link
        if( ! function_exists ( 'mnmlwp_shortcode_button_link' ) )
        {
            function mnmlwp_shortcode_button_link( $atts )
            {
                extract( shortcode_atts( array (
                    'text' => '',
                    'href' => '',
                    'class' => '',
                    'type' => '',
                    'style' => '',
                    'target' => '',
                ), $atts ) );

                if( ! empty( $type ) ) {
                    $types_arr = explode(' ', $type);
                    
                    foreach( $types_arr as $key => $t )
                    {
                        if( ! stristr($t, ' mnmlwp-btn-') ) {
                            $types_arr[$key] = ' mnmlwp-btn-' . $t;
                        }
                    }
                    
                    $types = implode(' ', $types_arr);
                } else {
                    $types = '';
                }

                $target = $target ? 'target="' . $target . '"' : '';

                return '<a class="mnmlwp-btn ' . $class . ' ' . $types . '" style="' . $style . '" href="' . $href . '" ' . $target . '>' . do_shortcode( $text ) . '</a>';
            }
        }

        add_shortcode ( 'button', 'mnmlwp_shortcode_button_link' );
        add_shortcode ( 'btn', 'mnmlwp_shortcode_button_link' );

        // Highlight
        function mnmlwp_shortcode_highlight( $atts, $content = null )
        {
            extract( shortcode_atts( array (
                'style' => '',
                'class' => '',
            ), $atts ) );

            return '<span class="mnmlwp-highlight ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</span>';
        }

        add_shortcode( 'highlight', 'mnmlwp_shortcode_highlight' );

        // Images
        if( ! function_exists ( 'mnmlwp_image_shortcode' ) )
        {
            function mnmlwp_image_shortcode( $atts )
            {
                extract( shortcode_atts( array (
                    'url' => '',
                    'src' => '',
                    'target' => '',
                    'href' => '',
                    'class' => '',
                    'align' => '',
                    'caption' => '',
                    'style' => '',
                    'alt' => '',
                    'title' => '',
                    'caption' => '',
                    'lightbox' => '',
                    'gallery' => '',
                    'width' => '',
                    'height' => '',
                ), $atts ) );

                if( empty( $gallery ) )
                    $gallery = 'Lightbox';

                if( ! empty( $href ) && empty ( $src ) )
                    $src = $href;

                if( ! $target )
                    $target = $src;

                $wrapper_class = '';

                if( $align === 'left')
                    $wrapper_class = ' alignleft';

                if( $align === 'right')
                    $wrapper_class = ' alignright';

                if( $align === 'center')
                    $wrapper_class = ' aligncenter';

                if( $width )
                    $style .= ';width:' . $width . 'px';

                if( $height )
                    $style .= ';height:' . $height . 'px';

                $html = '<div class="mnmlwp-image-wrapper ' . $wrapper_class . ' ' . $class . '">';

                if( filter_var( $lightbox, FILTER_VALIDATE_BOOLEAN ) )
                    $html .= '<a href="' . $target . '" data-title="' . $title . '" data-lightbox="' . $gallery . '">';

                if( ! empty( $url ) ) {
                    $html .= '<img src="' . $url . '" class="' . $class . '" alt="' . $alt . '" title="' . $title . '" style="' . $style . '">';
                } elseif( ! empty( $src ) ) {
                    $html .= '<img src="' . $src . '" class="' . $class . '" alt="' . $alt . '" title="' . $title . '" style="' . $style . '">';
                }

                if( $caption )
                {
                    $html .= '<span class="mnmlwp-image-caption">' . $caption . '</span>';
                }

                if( filter_var( $lightbox, FILTER_VALIDATE_BOOLEAN ) )
                    $html .= '</a>';

                $html .= '</div>';

                return $html;
            }
        }

        add_shortcode( 'img', 'mnmlwp_image_shortcode' );


        // Lightbox Gallery Wrapper
        if( ! function_exists ( 'mnmlwp_gallery_wrapper' ) )
        {
            function mnmlwp_gallery_wrapper( $atts, $content )
            {
                extract( shortcode_atts( array (
                    'class' => '',
                    'style' => '',
                ), $atts ) );

                $html = '<div class="mnmlwp-gallery-wrapper ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>';

                return $html;
            }
        }

        // add_shortcode('lightbox-gallery', 'mnmlwp_gallery_wrapper');

        // Responsive YouTube Video
        if( ! function_exists ( 'mnmlwp_shortcode_responsive_youtube' ) )
        {
            function mnmlwp_shortcode_responsive_youtube( $atts )
            {
                extract( shortcode_atts( array (
                    'identifier' => '',
                    'id' => '',
                    'class' => '',
                    'style' => '',
                    'cover' => '',
                ), $atts ) );

                if( ! $identifier && $id ) {
                    $identifier = $id;
                }

                $iframe_html = $cover ? '' : '<iframe src="//www.youtube.com/embed/' . $identifier . '?autoplay=1&rel=0" height="240" width="320" allowfullscreen=""></iframe>';
                $cover_html = $cover ? '<div class="mnmlwp-cover" style="background:url(' . $cover . ')"></div><div class="mnmlwp-cover-play-button"><img src="' . mnmlwp_assets_url() . '/img/play.png" alt="" /></div>' : '';
                
                return '<div class="mnmlwp-dont-print ' . $class . '"><div class="mnmlwp-video-container ' . $class . '" style="' . $style . '" data-id="' . $identifier . '" data-platform="youtube">' . $cover_html . $iframe_html . '</div></div>';                
            }
        }

        add_shortcode ( 'youtube', 'mnmlwp_shortcode_responsive_youtube' );

        // Responsive Vimeo Video
        if( ! function_exists ( 'mnmlwp_shortcode_responsive_vimeo' ) )
        {
            function mnmlwp_shortcode_responsive_vimeo( $atts )
            {
                extract( shortcode_atts( array (
                    'identifier' => '',
                    'id' => '',
                    'class' => '',
                    'style' => '',
                    'cover' => '',
                ), $atts ) );

                if( ! $identifier && $id ) {
                    $identifier = $id;
                }

                $iframe_html = $cover ? '' : '<iframe src="https://player.vimeo.com/video/' . $identifier . '" allowfullscreen=""></iframe>';
                $cover_html = $cover ? '<div class="mnmlwp-cover" style="background:url(' . $cover . ')"></div><div class="mnmlwp-cover-play-button"><img src="' . mnmlwp_assets_url() . '/img/play.png" alt="" /></div>' : '';

                return '<div class="mnmlwp-dont-print ' . $class . '" style="' . $style . '"><div class="mnmlwp-video-container ' . $class . '" data-id="' . $identifier . '" data-platform="vimeo">' . $cover_html . $iframe_html . '</div></div>';
            }
        }

        add_shortcode ( 'vimeo', 'mnmlwp_shortcode_responsive_vimeo' );

        // SoundCloud
        if( ! function_exists('mnmlwp_shortcode_soundcloud') )
        {
            function mnmlwp_shortcode_soundcloud( $atts ) {
                extract( shortcode_atts( array (
                    'width' => '800',
                    'height' => '450',
                    'params' => '',
                    'url' => '',

                ), $atts ) );

                $html = '<div class="mnmlwp-soundcloud-wrapper" style="height:' . $height . 'px">';
                $html .= '<iframe width="' . $width . '" height="' . $height . '" src="https://w.soundcloud.com/player/?url=' . $url . '&amp;' . $params . '"></iframe>';
                $html .= '</div>';

                return $html;
            }
        }

        remove_shortcode('soundcloud', 'soundcloud');
        add_shortcode('soundcloud', 'mnmlwp_shortcode_soundcloud');

        // Gallery
        if( ! function_exists('mnmlwp_shortcode_gallery') )
        {
            function mnmlwp_shortcode_gallery( $atts ) {
                extract( shortcode_atts( array (
                    'ids' => '',
                    'gallery' => '',
                    'columns' => 4,
                    'spacing' => 3,
                    'image_size' => '',
                ), $atts ) );

                if( ! $gallery )
                    $gallery = 'Lightbox';

                if( ! $ids )
                    return '<p>' . esc_html__('Missing ids in shortcode [gallery ids="1,2,3..."]', 'mnmlwp-shortcodes') . '</p>';

                $available_size = has_image_size( 'mnmlwp-640' ) ? 'mnmlwp-640' : 'medium';
                $image_size = ( $image_size && has_image_size( $image_size ) ) ? $image_size : $available_size;
        
                $original_size = has_image_size( 'mnmlwp-1440' ) ? 'mnmlwp-1440' : 'mnmlwp-1680';
                    
                $ids = explode(',', $ids);

                foreach( $ids as $key => $id )
                {
                    if( ! wp_get_attachment_image_src( $id, 'thumb' ) )
                        unset( $ids[$key] );
                }

                $html = '<div class="mnmlwp-gallery">';

                foreach( $ids as $key => $id )
                {
                    $key++;

                    $url = wp_get_attachment_image_src( $id, $image_size );
                    $url_original = wp_get_attachment_url( $id, $original_size );

                    $title = get_the_title( $id );

                    $width = (100/(int)$columns-$spacing) + $spacing/$columns;
                    $padding_top = 56.25/(int)$columns;

                    $margin = $key % $columns === 0 ? 0 : $spacing;

                    $html .= '<a href="' . $url_original . '" class="mnmlwp-gallery-item mnmlwp-lightbox" data-lightbox="' . $gallery . '" data-title="' . $title . '" style="background:url(' . $url[0] . ') no-repeat center center;width:' . $width . '%;margin-right:' . $margin . '%;padding-top: ' . $padding_top . '%;"></a>';

                    if( $key % $columns === 0 )
                        $html .= do_shortcode('[clear_columns]');
                }

                $html .= '</div>';

                return $html;
            }
        }

        remove_shortcode('gallery', 'gallery');
        add_shortcode('gallery', 'mnmlwp_shortcode_gallery');

        // Preformatted
        if( ! function_exists ( 'mnmlwp_shortcode_pre' ) )
        {
            function mnmlwp_shortcode_pre( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'class' => '',
                    'style' => '',
                ), $atts ) );
                
                $content = str_replace('<br />', "\r", $content);
                
                $content = '<pre class="mnmlwp-code ' . $class . '" style="' . $style . '">' . htmlentities( $content ) . '</pre>';

                return shortcode_unautop( $content );
            }
        }

        add_shortcode('pre', 'mnmlwp_shortcode_pre');
        add_shortcode('code', 'mnmlwp_shortcode_pre');

        // Flyout Wrapper
        if( ! function_exists('mnmlwp_shortcode_flyouts') )
        {
            function mnmlwp_shortcode_flyouts( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'accordion' => '',
                    'close_all' => '',
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                $class .= filter_var( $accordion, FILTER_VALIDATE_BOOLEAN ) ? ' mnmlwp-accordion' : '';
                $class .= filter_var( $close_all, FILTER_VALIDATE_BOOLEAN ) ? ' mnmlwp-accordion--close-all' : '';

                return shortcode_unautop( '<div class="mnmlwp-flyouts ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</div>' );
            }
        }

        add_shortcode( 'flyouts', 'mnmlwp_shortcode_flyouts' );

        // Flyout Containers
        if( ! function_exists ( 'mnmlwp_shortcode_flyout' ) )
        {
            function mnmlwp_shortcode_flyout( $atts, $content )
            {
                extract( shortcode_atts( array (
                    'title' => '',
                    'active' => '',
                    'class' => '',
                    'style' => '',
                ), $atts ) );

                if( filter_var( $active, FILTER_VALIDATE_BOOLEAN ) )
                    $class .= ' active';

                $html = '<div class="mnmlwp-flyout ' . $class . '" style="' . $style . '">';
                $html .= '<div class="mnmlwp-flyout-title">' . do_shortcode( $title ) . '</div>';
                $html .= '<div class="mnmlwp-flyout-content">' . do_shortcode( $content ) . '</div>';
                $html .= '</div>';

                return shortcode_unautop( $html );
            }
        }

        add_shortcode( 'flyout', 'mnmlwp_shortcode_flyout' );

        // Get Posts
        if( ! function_exists('mnmlwp_shortcode_get_posts') )
        {
            function mnmlwp_shortcode_get_posts( $atts )
            {
                extract( shortcode_atts( array (
                    'cat' => '',
                    'hide_images' => '',
                    'max' => '',
                ), $atts ) );
                
                if( ! isset( $max ) )
                    $max = -1;

                $format = filter_var( $hide_images, FILTER_VALIDATE_BOOLEAN ) ? 'no-images' : 'default';

                return mnmlwp_get_posts( $cat, $format, $max );
            }
        }

        add_shortcode( 'get_posts', 'mnmlwp_shortcode_get_posts' );
        
        // Ordered List Shortcode
        if( ! function_exists('mnmlwp_shortcode_ol') )
        {
            function mnmlwp_shortcode_ol( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<ol class="mnmlwp-list ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</ol>';
            }
        }

        add_shortcode( 'ol', 'mnmlwp_shortcode_ol' );
        
        // Unordered List Shortcode
        if( ! function_exists('mnmlwp_shortcode_ul') )
        {
            function mnmlwp_shortcode_ul( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<ul class="mnmlwp-list ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</ul>';
            }
        }

        add_shortcode( 'ul', 'mnmlwp_shortcode_ul' );
        
        // List Item Shortcode
        if( ! function_exists('mnmlwp_shortcode_li') )
        {
            function mnmlwp_shortcode_li( $atts, $content = null )
            {
                extract( shortcode_atts( array (
                    'style' => '',
                    'class' => '',
                ), $atts ) );

                return '<li ' . $class . '" style="' . $style . '">' . do_shortcode( $content ) . '</li>';
            }
        }

        add_shortcode( 'li', 'mnmlwp_shortcode_li' );
        
    }
}

new MNMLWP_Shortcodes;
