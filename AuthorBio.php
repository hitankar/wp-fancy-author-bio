<?php
namespace WPPlugin;

/** 
 * Class to add rich text editor to Author bio/description
 * 
 * @package WPPlugin
 *  @author Hitankar Ray
 * @version $Revision: 1.0.0 $ 
 * @access public 
 */
class AuthorBio
{
    private static $instance = null;
    
    private function __construct()
    {
        // add_action( 'show_user_profile', array( $this, 'author_bio' ) );
        // edit_action( 'edit_user_profile', array( $this, 'author_bio' ) );
        add_action('admin_head', array( $this, 'author_bio' ));
        remove_filter('pre_user_description', 'wp_filter_kses');
        add_filter( 'pre_user_description', 'wp_filter_post_kses' );
        add_filter('get_the_author_description', 'wpautop');
    }

    // singleton plugin instance mmethod
    public static function getInstance()
    {

        if (self::$instance == null)
        {
        self::$instance = new AuthorBio();
        }

        return self::$instance;
    }

    // set wp_editor to author bio/descrtiopion
    public function author_bio($user) {
        if ( basename($_SERVER['PHP_SELF']) == 'profile.php' || basename($_SERVER['PHP_SELF']) == 'user-edit.php' && function_exists('wp_tiny_mce') ) {
            echo "<script>jQuery(document).ready(function($){ $('#description').remove();});</script>";
            $settings = array(
                'tinymce' => array(
                    'toolbar1' => 'bold,italic,bullist,numlist,link,unlink,formatselect,cleanup,link,unlink',
                    'toolbar2' => '',
                    'toolbar3' => '',
                    'toolbar4' => '',
                ),
                'wpautop' => true,
                'media_buttons' => false,
                'quicktags' => true,
                'force_p_newlines' => true,
            );
            $description = get_user_meta( $user->ID, 'description', true);
            wp_editor( $description, 'description', $settings );
        }
    }
}
