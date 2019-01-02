<?php
	class MaviTmKategori extends WP_Widget{
		
		public function MaviTmKategori(){
			$widget_ops = array('classname' => 'widget_MaviTmKategori', 'description' => __('Kategori veya kategorilerinizi listeleyin'));
			$control_ops = array('width' => 390, 'height' => 350);
			$this->WP_Widget('MaviTmKategori', __('MaviTm - Kategori List'), $widget_ops, $control_ops);
		}
		
		public function widget($args, $instance){
			extract($args);
			
			$modFile = get_template_directory().'/view/kategoriView.php';
			if(file_exists($modFile) && is_file($modFile)){
				include_once($modFile);
			}else{ return ;}
			
			$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
			

			$pozisyonlar = array(
				'indexust' => '980','singleust' => '980','categoryenalt' => '980','categoryust' => '980','indexenalt' => '980','singleenalt' => '980',
				//250
				'indexsag' => '250','categorysag' => '250','singlesag' => '240',
				'manset' => '480','categorymanset' => '480',
				//230
				'mansetsag' => '230','categorymansetsag' => '230',
				//720
				'indexorta' => '720','categoryorta' => '720','categoryalt' => '720','singleyorumalt' => '720','singlealt' => '720','singleorta' => '720',				
			);
			
			//980, 480
			
			
			//if(count($kategoriler) == 1){switch ($pozisyonlar[$args['id']]){case '980': break;case '720':break;case '480':break;case '250':break;case '230':break;}}elseif(count($kategoriler) > 1){switch ($pozisyonlar[$args['id']]){case '980': case '720': break;case '480': case '230': break;default:break;}}else{}			
			echo $before_widget;
			
			if($instance['baslikKullanma']){
				echo str_replace('defaultTitle','title title'.$instance['baslikKullanma'],$before_title);		
				echo $title;
				echo $after_title;
			}
			$instance['poz'] = $pozisyonlar; 
			$instance['arg'] = $args; 
			echo '<div class="katWidget'.($pozisyonlar[$args['id']] == true ? $pozisyonlar[$args['id']] : '980').'">';
				widgetKategoriListesi($instance);
			echo '</div>';
			echo $after_widget;
		}
		
		public function update($new_instance, $old_instance){
			$instance = $old_instance;
			
			$instance['baslikKullanma'] = $new_instance['baslikKullanma'];
			$title = stripslashes(trim($new_instance["title"]));
			$instance["title"] = strip_tags($title);// == '' ? $cat->cat_name : $title;
			
			$instance["kategoriler"] = implode(',',$new_instance["kategoriler"]);
			
			$instance['postLimit'] = $new_instance['postLimit'];
			$instance['postSutun'] = $new_instance['postSutun'];
			$instance['titleLimit'] = $new_instance['titleLimit'];
			$instance['tetxLimit'] = $new_instance['tetxLimit'];
			$instance['postOrder'] = $new_instance['postOrder'];
			$instance['postOrderBy'] = $new_instance['postOrderBy'];
			return $instance;
		}
		
		public function form($instance){
			$defaults = array(
				"title" => "Kategoriler",
				"postLimit" => 5,
				"titleLimit" => 3,
				"tetxLimit" => 6
			);
			$instance = wp_parse_args((array)$instance, $defaults);
			?>
				<p><img src="<?php bloginfo('template_directory');?>/images/video.jpg" alt="widget-video" align="right" style="padding: 1px; margin: 0px 0px 10px 10px; border: 1px solid #C0CCD3; width:100%;" />
				<div style="clear: both; padding-bottom: 15px; margin-bottom: 20px; border-bottom: 1px solid #C0CCD3;"></div></p>
				<div>
					<label for="<?php echo $this->get_field_id('baslikKullanma'); ?>"><?php _e('<p><b>Basl&#305;k Bi&ccedil;imi:</b></p>', 'MaviTm'); ?></label>
					<select id="<?php echo $this->get_field_id('baslikKullanma'); ?>" name="<?php echo $this->get_field_name('baslikKullanma'); ?>" class="widefat baslikOnOff">
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
				</div>
				<div style="display:none;" class="baslikAcKapa">
					<label for="<?php echo $this->get_field_id("title"); ?>"><?php _e( '<p><b>Ba&#351;l&#305;k :</b></p>' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
					<br /><?php _e( 'Bu widget i&ccedil;in bir ba&#351;l&#305;k girin' ); ?><br /><br />
					
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom:10px; border-bottom: 1px solid #C0CCD3;"></div>
				<div class="katSec">
					<label for="<?php echo $this->get_field_id("kategoriler"); ?>"><?php _e( '<p><b>Hangi Kategoriler :</b></p>' ); ?></label>
					<div style="clear: both;"></div>
					<select size="7" multiple="multiple" id="<?php echo $this->get_field_id('kategoriler'); ?>" name="<?php echo $this->get_field_name('kategoriler');?>[]" class="widefat">
						<?php
							$selectedCat = explode(',',$instance['kategoriler']);
							$kategoriler = get_categories( "hide_empty=0" );
							foreach ($kategoriler as $kat){?>
								<option <?php if (in_array($kat->cat_ID,$selectedCat)){ echo 'selected="selected"';} ?> value='<?php echo $kat->cat_ID;?>'><?php echo $kat->cat_name; ?></option>
							<?php }
						?>
					</select>
					<br /><?php _e( 'ctrl tu&#351;u ile birden fazla se&ccedil;im yapabilirsiniz' ); ?><br /><br />
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom:10px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
				
					<div>
						<label for="<?php echo $this->get_field_id("postLimit"); ?>">
						<?php _e( '<p><b>Bir kategori de ka&ccedil; tane ba&#351;l&#305;k olsun :</b></p>' ); ?>
						<input class="widefat" id="<?php echo $this->get_field_id("postLimit"); ?>" name="<?php echo $this->get_field_name("postLimit"); ?>" type="text" value="<?php echo esc_attr($instance["postLimit"]); ?>" />
						<br /><?php _e( 'Limit.' ); ?><br /></label>
					</div>
					
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<div style="width:150px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id("titleLimit"); ?>"><?php _e( '<p><b>Baslik kelime! sayisi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("titleLimit"); ?>" name="<?php echo $this->get_field_name("titleLimit"); ?>" type="text" value="<?php echo esc_attr($instance["titleLimit"]); ?>" />
						<br /><?php _e( 'h3 baslik.' ); ?><br />
					</div>
					<div style="width:150px; float:left; margin-right:19px;">
						<label for="<?php echo $this->get_field_id("tetxLimit"); ?>"><?php _e( '<p><b>Text kelime! sayisi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("tetxLimit"); ?>" name="<?php echo $this->get_field_name("tetxLimit"); ?>" type="text" value="<?php echo esc_attr($instance["tetxLimit"]); ?>" />
						<br /><?php _e( 'p text.' ); ?><br />
					</div>
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
				
					<div style="width:120px; float:left;">
							<label for="<?php echo $this->get_field_id('postOrder'); ?>"><?php _e('<p><b>S&#305;ralanma :</b></p>', 'MaviTm'); ?>
							<select id="<?php echo $this->get_field_id('postOrder'); ?>" name="<?php echo $this->get_field_name('postOrder'); ?>" class='widefat'>
							<?php
								$postOrder = array(
									'date' => __('Eklenme tarihiyle s&#305;rala', 'MaviTm'),
									'id' => __('Post id ye g&ouml;re', 'MaviTm'),
									'author' => __('Ekleyen ismine g&ouml;re', 'MaviTm'),
									'title' => __('Ba&#351;l&#305;&#287;a g&ouml;re ', 'MaviTm'),
									'modified' => __('D&uuml;zenlenme tarihine g&ouml;re', 'MaviTm'),
									'comment_count' => __('Yorum say&#305;s&#305;na g&ouml;re', 'MaviTm'),
									'rand' => __('Rastgele', 'MaviTm'),
								);
								foreach ($postOrder as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postOrder'])) selected($type_id, $instance['postOrder']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'Field &#350;e&ccedil;imi.' ); ?><br /></label>
					</div>
				
					<div style="width:260px; float:right;">
							<label for="<?php echo $this->get_field_id('postOrderBy'); ?>"><?php _e('<p><b>S&#305;ralanma &#351;ekli :</b></p>', 'MaviTm'); ?>
							<select id="<?php echo $this->get_field_id('postOrderBy'); ?>" name="<?php echo $this->get_field_name('postOrderBy'); ?>" class='widefat'>
							<?php
								$postSutun = array(
									'DESC' => __('Son eklenenden ilk eklenene (DESC)', 'MaviTm'),
									'ASC' => __('&#304;lk eklenenden son eklenene (ASC)', 'MaviTm'),
									'0' => __('Rastgele se&ccedil;im i&ccedil;in tan&#305;ms&#305;z', 'MaviTm')
								);
								foreach ($postSutun as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postOrderBy'])) selected($type_id, $instance['postOrderBy']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'S&#305;ralama &ouml;nceli&#287;i.' ); ?><br /></label>
					</div>
					
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom:10px; border-bottom: 1px solid #C0CCD3;"></div>
				<script type="text/javascript">
					$(".baslikOnOff").change(
						function(){var neSecti = $(this).val();
							if(neSecti != "0"){$(".baslikAcKapa").slideDown("normal");}else{$(".baslikAcKapa").slideUp("normal");}
						}
					);
				</script>
			<?php
		}
	}
?>