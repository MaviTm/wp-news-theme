<?php
class MaviTmEmbed extends WP_Widget {

	function MaviTmEmbed() {
		$widget_ops = array('classname' => 'widget_mavitmEmbed', 'description' => __('Embed kod ile video veya Reklam ekleme'));
		$control_ops = array('width' => 350, 'height' => 350);
		$this->WP_Widget('mavitmEmbed', __('MaviTm - Kod Blok'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$text = apply_filters( 'widget_execphp', $instance['text'], $instance );
		echo $before_widget;
		
		if($instance['baslikKullanma']){
			echo str_replace('defaultTitle','title title'.$instance['baslikKullanma'],$before_title);		
			if( $instance["title"] ){echo $instance["title"];}
			else{echo $title;}
			echo $after_title;
		}
	?>			
		<?php
			$tempDir = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
			if(!empty($instance['sablonSec'])){
				if(file_exists($tempDir.$instance['sablonSec']) && is_file($tempDir.$instance['sablonSec'])){
					include_once($tempDir.$instance['sablonSec']);	
				}
			}
			echo $instance['filter'] ? wpautop($text) : $text; 
		?>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
		$instance['text'] = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );
		$instance['filter'] = isset($new_instance['filter']);
		$instance['baslikKullanma'] = $new_instance['baslikKullanma'];
		$instance['sablonSec'] = $new_instance['sablonSec'];
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
?>


<p><img src="<?php bloginfo('template_directory');?>/images/video.jpg" alt="widget-video" align="right" style="padding: 1px; margin: 0px 0px 10px 10px; border: 1px solid #C0CCD3; width:100%;" />
<div style="clear: both; padding-bottom: 15px; margin-bottom: 20px; border-bottom: 1px solid #C0CCD3;"></div></p>
		<p>
			<label for="<?php echo $this->get_field_id("title"); ?>">
			<?php _e( '<p><b>Ba&#351;l&#305;k :</b></p>' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
			<br /><br /><?php _e( 'Bu widget i&ccedil;in bir ba&#351;l&#305;k girin veya varsay&#305;lan metin kullanmak i&ccedil;in bo&#351; b&#305;rak&#305;n.' ); ?><br /><br />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('baslikKullanma'); ?>"><?php _e('Baslik:', 'MaviTm'); ?></label>
			<select id="<?php echo $this->get_field_id('baslikKullanma'); ?>" name="<?php echo $this->get_field_name('baslikKullanma'); ?>" class='widefat'>
			<?php
				$options = array(
					'0' => __('Baslik Kullanma', 'MaviTm'),
					'mavi' => __('Mavi', 'MaviTm'),
					'kirmizi' => __('Kirmizi', 'MaviTm'),
					'yesil' => __('Yesil', 'MaviTm'),
					'turuncu' => __('Turuncu', 'MaviTm'),
				);
				foreach ($options as $option_name => $option_text) {
			?>
							<option <?php if (isset($instance['baslikKullanma'])) selected($option_name, $instance['baslikKullanma']); ?> value='<?php echo $option_name;?>'><?php echo $option_text; ?></option>
			<?php
					}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">	<?php _e( '<b>Video Embed Kodu:</b><br />' ); ?>
			<i><?php _e( 'Ekleyece&#287;iniz yerin boyutuna g&ouml;re bi&ccedil;imlendirin.' ); ?></i></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
		</p>
		
		<?php 
			$tempDir = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
			$fileTut = scandir($tempDir);
			$noIn = array('index.php','attachment.php','category.php','comments.php','footer.php','functions.php','header.php','page.php','search.php','single.php','tag.php','css.php');
			$files[0] = __('Sablon Kullanma','MaviTm');
			foreach ($fileTut as $file){
				if(in_array($file,$noIn)){continue;}
				if(strrchr($file,'.') != '.php'){continue;}
				if(is_file($tempDir.$file)){
					$files[$file] = __($file,'MaviTm');
				}
			}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('sablonSec'); ?>"><?php _e('Sablon:', 'MaviTm'); ?><br />
			<i>Gecerli template dizini icerisindeki ozel php dosyalariniz</i></label>
			<select id="<?php echo $this->get_field_id('sablonSec'); ?>" name="<?php echo $this->get_field_name('sablonSec'); ?>" class='widefat'>
			<?php
				foreach ($files as $option_name => $option_text) {
			?>
							<option <?php if (isset($instance['sablonSec'])) selected($option_name, $instance['sablonSec']); ?> value='<?php echo $option_name;?>'><?php echo $option_text; ?></option>
			<?php
					}
			?>
			</select>
		</p>	
<?php
	}
}
?>
