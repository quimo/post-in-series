<?php

class PostSeries {
    
    private static $_instance = null;

    public static function instance() {
        
        
        if (!function_exists('get_field')) {
            //TODO: mostrare un messaggio di errore
            return false;
        }
        

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        add_shortcode('post-series-message', array('PostSeries', 'getSeriesMessage'));
        //add_action('wp_enqueue_scripts', array('PostSeries', 'addCustomStylesAndScripts'));
        register_activation_hook( __FILE__, array('PostSeries', 'activation'));
        register_deactivation_hook( __FILE__, array('PostSeries', 'deactivation'));

    }

    public static function activation() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'post-series-puntate',
                'title' => 'Puntate',
                'fields' => array(
                    array(
                        'key' => 'field_5edcf048d3300',
                        'label' => 'Numero puntata',
                        'name' => 'post_series_episode_number',
                        'type' => 'number',
                        'instructions' => 'Numero dell\'episodio nella serie',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_5edcf07878261',
                        'label' => 'Articolo successivo',
                        'name' => 'post_series_next_episode',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'post',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_5edcf0a678262',
                        'label' => 'Articolo precedente',
                        'name' => 'post_series_previous_episode',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'post',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'side',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ));   
        }
    }

    public static function deactivation() {
        if (function_exists('acf_add_local_field_group')) {
            acf_remove_local_field_group('post-series-puntate');
        }

    }

    public static function getSeriesMessage() {
        global $post; 
        $episode = get_field('post_series_episode_number', $post->ID);
        if ($episode && $episode != 0) {
            echo __('Puntata', 'post-series');
            echo ' ' . $episode;
            echo __(' di', 'post-series');
            echo ' ' . self::getSeriesEpisodes($post->ID);
        }
        

    }

    private static function getSeriesEpisodes($post_id) {
    
        $previous_episode_id = get_field('post_series_previous_episode', $post_id);
        $first_episode_id = $post_id;

        //mi sposto al primo episodio della serie
        if (!empty($previous_episode_id)) {
            do {
                $first_episode_id = $previous_episode_id;
                $previous_episode_id = get_field('post_series_previous_episode', $previous_episode_id);
            } while (!empty($previous_episode_id));
        }

        //scorro gli episodi e li conto
        $next_episode_id = get_field('post_series_next_episode', $first_episode_id);
        $episodes = 1;
        while (!empty($next_episode_id)) {
            $next_episode_id = get_field('post_series_next_episode', $next_episode_id);
            $episodes++;
        }
        
        return $episodes;
    
    }

    public static function addCustomStylesAndScripts() {
        
        wp_enqueue_style('post-series', plugin_dir_url(__FILE__) . '../assets/css/style.css', array(), '1.0.0');
        wp_enqueue_script('post-series', plugin_dir_url(__FILE__) . '../assets/js/init.js', array('jquery'), '1.0.0' ,true);
        
    }


}

PostSeries::instance();