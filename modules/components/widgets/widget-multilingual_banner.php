<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Popular_Posts extends Widget_Base {

	public function get_name() {

		return 'popular-posts';
	}

	public function get_title() {
		return __( 'Multilingual Banner', 'elementor-custom-widget' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	protected function getAllBanners(){  
		
		$BannerArray = array();

		$wp_query = new \WP_Query( array(
						
			'post_type'      => 'banner',   
			'posts_per_page' => -1 , 
		));   
		
		if ( $wp_query->have_posts() ) : 
			$looper = 0;
			while ( $wp_query->have_posts() ) :
				$wp_query->the_post(); 
					$BannerArray[$looper]= array("banner_id" => get_the_ID(), "banner_title" => get_the_title()); 
					$looper++;
			endwhile;
		endif; 

		wp_reset_postdata();

		return $BannerArray;

	}

	protected function getBannerDetails(){  

		$settings 	= $this->get_settings_for_display();  
		$BannerArray = array(); 

		global $wpdb;
		$results = $wpdb->get_results( 
			"SELECT * FROM 
				{$wpdb->prefix}plugin_custom_banner 
				WHERE 
				banner = ".$settings['banner_cpt']." 
				and language LIKE '". substr(get_bloginfo("language"), 0, 1)."%'", 
			OBJECT ); 
			
		$looper = 0; 

		foreach( $results as $posts ) { 
			// $posts->language;
			$BannerArray[$looper]= 
				array(
					"language" 		=> $posts->language, 
					"link" 			=>  $posts->link,
					"image_path" 	=> $posts->image_path
				); 
			$looper ++;
		}  
		return $BannerArray; 
	}

	protected function _register_controls() {
		$display_banner 	= $this->getAllBanners(); 
		$banner_data = [];
		foreach ($display_banner as $key => $value) {
			$banner_data[$value['banner_id']] = $value['banner_title'];
		}

		$this->start_controls_section(
			'section_banner_select',
			[
				'label' => esc_html__( 'Details', 'elementor-custom-widget' ),
			]
		);

		$this->add_responsive_control(
			'banner_cpt',
			[
				'label' => __( 'Select Banner', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,  
				'options' => $banner_data, 
			]
		);   
		$this->add_responsive_control(
			'banner_alignment',
			[
				'label' => __( 'Alignment', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'center', 
				'options' => [
					'left' => [
						'title' => __( 'Left', 'plugin-name' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'plugin-name' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'plugin-name' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->add_control(
			'banner_caption',
			[
				'label' => __( 'Caption', 'plugin-domain' ),
				'type' => Controls_Manager::TEXT, 
				'placeholder' => 'Any Text ...'
			]
		);   

		$this->add_responsive_control(
			'banner_animation',
			[
				'label' => __( 'Animation', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'animate__fadeIn',
				'options' => [
					'none'  						=> __( 'None', 'plugin-domain' ),    
					'animate__fadeIn'  				=> __( 'Fade In', 'plugin-domain' ),    
					'animate__fadeInDown'  			=> __( 'Fade Down', 'plugin-domain' ),    
					'animate__fadeInDownBig'  		=> __( 'Fade Down Big', 'plugin-domain' ),    
					'4animate__fadeInLeft'  		=> __( 'Fade In Left', 'plugin-domain' ),    
					'animate__fadeInRight'  		=> __( 'Fade In Right', 'plugin-domain' ),    
					'animate__fadeInUp'  			=> __( 'Fade In Up', 'plugin-domain' ),    
					'animate__fadeInUpBig'  		=> __( 'Fade In Up Big', 'plugin-domain' ),    
					'animate__fadeInTopLeft'  		=> __( 'Fade In Top Left', 'plugin-domain' ),    
					'animate__fadeInTopRight'  		=> __( 'Fade In Top Right', 'plugin-domain' ),    
					'animate__fadeInBottomLeft'  	=> __( 'Fade In Button Left', 'plugin-domain' ),    
					'animate__fadeInBottomRight'  	=> __( 'Fade In Bottom Right', 'plugin-domain' ),    
				],
			]
		);  

		 

		$this->end_controls_section(); 


	}  

	protected function render( $instance = [] ) {
		 
		$settings = $this->get_settings_for_display(); 
		$banner_details = $this->getBannerDetails(); 
		
		?> 
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"> 
			<style>
				.image-banner-holder{
					text-align: center;
					width: 100%; 
				}
				.image-banner-holder a img{
					width: 728px;
					height: auto;
					transition: width 1s;
				} 
				.center{
					text-align:center;
				}
				.right{
					text-align:right;
				}
				.left{
					text-align:left;
				}
				@media only screen 
					and (max-device-width: 768px){ 
						.image-banner-holder{
							text-align: center;
							width: 100%; 
						}
						.image-banner-holder a img{
							width: 400px;
							height: auto;
							transition: width 1s;
						} 
				}
				@media only screen 
					and (max-device-width: 450px){ 
						.image-banner-holder{
							text-align: center;
							width: 100%; 
						}
						.image-banner-holder a img{
							width: 400px;
							height: auto;
							transition: width 1s;
						}
				}
 
			</style> 

			<div 
				class="elementor-element elementor-element-1bf6d0e elementor-widget elementor-widget-image animate__animated <?php echo $settings['banner_animation'];?> <?php echo $settings['banner_alignment'];?>" 
				data-id="1bf6d0e" 
				data-element_type="widget" 
				data-widget_type="image.default">
				
				<div class="elementor-widget-container">
					<div class="elementor-image">
						<a href="<?php print_r('http://'.$banner_details[0]["link"]) ;?>" target="_blank">
							<img 
								src="<?php print_r($banner_details[0]["image_path"]) ;?>" 
								alt="">
							 
							<noscript>
							<img 
								width="728" 
								height="90" 
								src="<?php print_r('http://'.$banner_details[0]["link"]) ;?>" 
								class="attachment-large size-large" alt="eToro-US-desktop" 
								srcset="
									<?php print_r('http://'.$banner_details[0]["link"]) ;?> 728w, 
									<?php print_r('http://'.$banner_details[0]["link"]) ;?> 300w" 
								sizes="(max-width: 728px) 100vw, 728px" />
							</noscript>
							<figcaption class="widget-image-caption wp-caption-text"><?php echo $settings['banner_caption'];?></figcaption>
						</a>
					</div>
				</div>

			</div> 
		  
		<?php

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widget_Popular_Posts() );
