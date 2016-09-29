<?php
/**
 * @package NewsImages
 * @version 1.0
 */
/*
Plugin Name: News Images
Author: Kiera Howe
Version: 1.0
Author URI: http://www.kierahowe.com/
*/

class newsimages extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'newsimages',
			'description' => 'News Images',
		);
		parent::__construct( 'newsimages', 'News Images', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		if ($instance['offset'] == "") { $instance['offset'] = 0; }
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		?>
		<div class="newsimg" >
			<?php if ($instance['contentcount'] == 1) { 

				$args = array ("post_type" => "newsarticles", "posts_per_page" => 1, "offset" => $instance['offset']);
				$the_query = new WP_Query( $args );

				$out = array ();
				if ( $the_query->have_posts() ) {
					$the_query->the_post();

					$post_image_id = get_post_thumbnail_id();
					if ($post_image_id) {
						$thumbnail = wp_get_attachment_image_src( $post_image_id, 'post-thumbnail', false);
						if ($thumbnail) (string)$thumbnail = $thumbnail[0];
					}
				?>
				<div class="offset1" style="background-image: url(<?php echo $thumbnail; ?>);">
					<h2><?php echo get_the_title(); ?></h2>
				</div>
				<?php 
					wp_reset_postdata();
				} else {
					
				}
				?>

			<?php } 
				if ($instance['contentcount'] == 4) { 
					$args = array ("post_type" => "newsarticles", "posts_per_page" => 4, "offset" => $instance['offset']);
					$the_query = new WP_Query( $args );

					$out = array ();
					if ( $the_query->have_posts() ) {
						while ($the_query->have_posts() ) { 
							$the_query->the_post();
							$thumbnail = "";
							$post_image_id = get_post_thumbnail_id();
							if ($post_image_id) {
								$thumbnail = wp_get_attachment_image_src( $post_image_id, 'post-thumbnail', false);
								if ($thumbnail) (string)$thumbnail = $thumbnail[0];
							}
							?>
								<div class="offset4" style="background-image: url(<?php echo $thumbnail; ?>);">
									<h2><?php echo get_the_title(); ?></h2>
								</div>
							<?php 
						}
					}
					wp_reset_postdata();
				} ?>
		</div>
 <?php 
		echo $args['after_widget'];
		
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$offset = ! empty( $instance['offset'] ) ? $instance['offset'] : __( '', 'text_domain' );
		$contentcount = ! empty( $instance['contentcount'] ) ? $instance['contentcount'] : __( '', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php _e( esc_attr( 'offset:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="numeric" value="<?php echo esc_attr( $offset ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'contentcount' ) ); ?>"><?php _e( esc_attr( 'Content Count:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'contentcount' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'contentcount' ) ); ?>" type="numeric" value="<?php echo esc_attr( $contentcount ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['offset'] = ( ! empty( $new_instance['offset'] ) ) ? strip_tags( $new_instance['offset'] ) : '';
		$instance['contentcount'] = ( ! empty( $new_instance['contentcount'] ) ) ? strip_tags( $new_instance['contentcount'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'newsimages' );
});

function newsimg_newsarticles() {
	$labels = array(
		'name'                  => _x( 'News Articles', 'News Article General Name', 'text_domain' ),
		'singular_name'         => _x( 'News Article', 'News Article Singular Name', 'text_domain' ),
		'menu_name'             => __( 'News Articles', 'text_domain' ),
		'name_admin_bar'        => __( 'News Article', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'News Articles', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'News Article', 'text_domain' ),
		'description'           => __( 'News Article Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array('title', 'editor',  'thumbnail'),
		'taxonomies'            => array( ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 80,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'newsarticles', $args );
}
add_action( 'init', 'newsimg_newsarticles', 0 );

