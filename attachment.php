<?php get_header(); require_once(get_template_directory().'/inc/MaviTmLoads.php'); $maviTm =& MaviTmLoads::factory('MaviTmLoads');?>
<div id="content" role="main">
	
	<?php if(is_dynamic_sidebar('singleust')){echo '<div class="blokUst">';dynamic_sidebar('singleust');echo '</div>';}?>
	<!-- main  -->
	<div class="siteMain">

		<?php if(is_dynamic_sidebar('singleorta')){echo '<div class="ortaBlok">';dynamic_sidebar('singleorta');echo '</div><div class="clear"></div>';}?>

		<!-- content  -->
		<div id="maviTmContent">
		<?php 
			have_posts(); the_post(); $etiketler = get_the_tags();
			$metadata = wp_get_attachment_metadata();
			$mime = get_post_mime_type();
			$postAdi = stripslashes(strip_tags(get_the_title( $post->post_parent )));
			$parentId = $post->post_parent;
			$attachments = get_children(array('post_parent'=>$post->post_parent));
			ksort($attachments);
			$toplamAttachment = count($attachments);
			$resimMime = array('image/png','image/gif','image/jpeg','image/x-pn','image/pjpeg','image/jpg');
			
			if(in_array($mime,$resimMime)){
				if(get_option('MaviTmAttachmentColorBox') != 1){
					$attachmentR = array_values($attachments);
					foreach ( $attachmentR as $k => $attachmentX ){if($attachmentX->ID == $post->ID) break;}
					$k++;
					if (count($attachmentR) > 1){
						if (isset($attachmentR[$k])){$next_attachment_url = get_attachment_link($attachmentR[$k]->ID );}
						else{$next_attachment_url = get_attachment_link($attachmentR[0]->ID);}
					}else{$next_attachment_url = wp_get_attachment_url();}
					unset($attachmentR);
					$attachmentLink = $next_attachment_url;
					$colorBoxJs = false;
				}else{
					$colorBoxJs = true;
					$attachmentLink = wp_get_attachment_url();
				}
			}
		?>
		
				<?php if(strlen(get_the_title()) > 60){$cHc = ' ek35';}else{$cHc = '';} ?>
				<div class="contentHead<?=$cHc;?>">	
				
					<h2 class="contentTitle"><?php echo $postAdi ; ?></h2>
					<div class="left postCat">
						<a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php echo $postAdi?>" rel="gallery"> &laquo; Konuya Geri D&ouml;n</a> | 
						<?php the_time('j F, Y'); ?> <?php the_time('g:ia'); ?>
					</div>
					
									
					<div class="fontA">
						<?php if(get_option('MaviTmAddthisOn')){ ?>
						<a class="addthis_button left" href="http://www.addthis.com/bookmark.php?v=250&amp;username=mavitm" target="_blank"><img src="<?php bloginfo('template_directory');?>/images/share.png" alt="" title="" /> Payla&#351;</a>
						<?php } ?>
						<a class="left" id="yorumYap"><img src="<?php bloginfo('template_directory');?>/images/comments_add.png" alt="" title="" /> Yorum Yap</a>
					</div>
					
					<div class="clear"></div>
				<!-- content head -->	
				</div>
				<?php if(in_array($mime,$resimMime)){ ?>
				<div class="clear"></div>
				<p class="attachment center">
					<a href="<?php echo $attachmentLink; ?>" title="<?php the_title_attribute(); ?>" class="attachmentPic" rel="attachment">
						<?php
							$attachment_width  = apply_filters( 'twentyten_attachment_size',720 );
							$attachment_height = apply_filters( 'twentyten_attachment_height', 720 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_width, $attachment_height ) ); // filterable image width with, essentially, no limit for image height.
						?>
					</a>
				</p>
				<?php 
					}else{ 
					$info = $attachments[$post->ID];
					if (get_post_meta($info->post_parent, OZELALAN, true) == true){
						if(strlen(get_post_meta($info->post_parent, OZELALAN, true)) > 10){
							$playerImg = get_post_meta($info->post_parent, OZELALAN, true);		
						}
					}elseif (has_post_thumbnail($post->post_parent)){
						$domsxe = simplexml_load_string(get_the_post_thumbnail($post->post_parent,'medium'));
						$thumbnailsrc = $domsxe->attributes()->src;
						$playerImg = $thumbnailsrc;
					}else{
						$playerImg = get_bloginfo("template_url").'/images/resimyok.jpg';
					}
					$videoen = get_option('MaviTmPlayerEn') > 0 ? get_option('MaviTmPlayerEn') : 720;
					$videoboy = get_option('MaviTmPlayerBoy') > 0 ? get_option('MaviTmPlayerBoy') : 300;
					$videoAuto = get_option('MaviTmPlayerAuto') > 0 ? get_option('MaviTmPlayerAuto') : 0;
					$maviTm ->vayaral('',$info->guid,$videoen,$videoboy,$videoAuto,$videoAuto,$playerImg);
					
				?>
				<p class="attachment center">
					<?=$maviTm->videover();//video olmasi sart degil (img,movie(swf dahil))?>
				</p>
				<?php } ?>	
				
				<div class="clear"></div>	
				<div class="mrBottom10"></div>		
				
				<p>
					<h3><?=$post->post_title;?></h3>
					<?php if(!empty($post->post_content)){the_content();} ?>
				</p>			
				
				<div class="clear"></div>	
				<div class="mrBottom10"></div>
				
				<?php if($toplamAttachment > 1){ ?>
						<ul class="pagenavi">
							<li><?php previous_image_link(false,'&laquo;'); ?></li>
							<?php $x = 1; foreach ( $attachments as $k => $attachment ) { if($attachment->ID == $post->ID){$cls = 'active';}else{$cls = '';}?>
								<li class="attachmentPage <?=$cls;?>">
									<a href="<?=get_attachment_link($attachments[$k]->ID);?>"><?=$x;?></a>
									<?php if($cls != 'active'){?>
									<a href="<?=$attachments[$k]->guid;?>" class="attachmentPic fullNone"></a>
									<?php }?>
								</li>
							<?php $x++;} ?>
							<li><?php next_image_link(false,'&raquo;'); ?></li>
						</ul>
				<?php } ?>
				
				
				<div class="clear"></div>	
				<div class="contentBottom">	
				
					<?php if(get_option('MaviTmAddthisOn')){ ?>
						<!-- AddThis Button BEGIN -->
						<div class="addthis_toolbox addthis_default_style ">
							<a class="addthis_button_preferred_1"></a>
							<a class="addthis_button_preferred_2"></a>
							<a class="addthis_button_preferred_3"></a>
							<a class="addthis_button_google_plusone_share"></a>
							<a class="addthis_button_delicious"></a>
							<a class="addthis_button_digg"></a>
							<a class="addthis_button_email"></a>
							<a class="addthis_button_compact"></a>
							<a class="addthis_counter addthis_bubble_style"></a>
						</div>
						<!-- AddThis Button END -->
					<?php }?>
					
					<div class="nav-next"><?php next_image_link(false,'Bir sonraki &raquo;'); ?></div>
					<div class="nav-previous"><?php previous_image_link(false,'&laquo; Bir &Ouml;nceki'); ?></div>
					
				</div>
				<div class="clear"></div>	
			
		<!-- content  -->
		</div>

		<?php if(is_dynamic_sidebar('singlealt')){echo '<div class="ortaBlok">';dynamic_sidebar('singlealt');echo '</div><div class="clear"></div>';}?>

		<!-- tab area  -->
		<ul class="contentTabUl" id="contentTabUl">
			<li><a>Di&#287;er <?=in_array($mime,$resimMime) ? 'Resimler' : 'Videolar';?></a></li>
			<li><a id="yorum">Yorumlar (<?php comments_number('0', '1', '%');?>)</a></li>
			<?php if(is_array($etiketler)){ ?><li><a>Aramalar</a></li><?php }?>

		</ul>
		
		<div id="singleTabDis" class="singleTabDis contentTabGrup">
			<div class="contentTabIc" id="digerHaberler">
				<?php /*burasi bos degil sonradan geliyor*/ ?>
			</div>
			
			<div class="contentTabIc none">
				<?php comments_template( '', true );?>
			</div>
			
			<?php if(is_array($etiketler)){ ?>
			<div class="contentTabIc none tagsGrup">
				<div>
					<?php foreach ($etiketler as $ar){echo '<a href="'.get_tag_link($ar->term_id).'" title="'.$ar->name.'" class="pButton">'.$ar->name.'</a>';}?>
					<div class="clear"></div>
				</div>
			</div>
			<?php } ?>
		</div>
		
		<script type="text/javascript">pageTabs('contentTabUl','singleTabDis','contentTabIc')</script>
		
		<div id="digerCopy" class="none fullNone">
			<?php 
				$catTut = get_the_category();
				
				if(in_array($mime,$resimMime)){
					$maviTm->digerResimlerBr($catTut,$parentId);
				}else{
					$maviTm->digerVideolarBr($catTut,$parentId);
				}
			?>
		<!-- tab area -->	
		</div>
		<div id="yorumForm"></div>
		<?php if(is_dynamic_sidebar('singleyorumalt')){echo '<div class="ortaBlok">';dynamic_sidebar('singleyorumalt');echo '</div><div class="clear"></div>';}?>
		
	<!-- main  -->	
	</div>

	<div class="sagAlan">
			<?php if(is_dynamic_sidebar('singlesag')){dynamic_sidebar('singlesag');}?>
	</div>

	<div class="clear"></div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		<?php if($colorBoxJs){ ?>
		$(".attachmentPic").colorbox({rel:'attachment',transition:"elastic",height:"90%",fixed:true});<?php }?>
		var buranAl = $("#digerCopy").html(); $("#digerHaberler").html(buranAl); $("#contentTabUl li:first a").click(); 
		$("#yorumYap").click(function(){ $("#yorum").click();	$.scrollTo( 'div#yorumForm', {duration:1000} ); });
	});
</script>

<?php get_footer(); ?>