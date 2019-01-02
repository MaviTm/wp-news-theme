<?php get_header(); require_once(get_template_directory().'/inc/MaviTmLoads.php'); $maviTm =& MaviTmLoads::factory('MaviTmLoads');?>
<div id="content" role="main">

	<?php if(is_dynamic_sidebar('singleust')){echo '<div class="blokUst">';dynamic_sidebar('singleust');echo '</div>';}?>
	
	<div class="siteMain">
			<?php if(is_dynamic_sidebar('singleorta')){echo '<div class="ortaBlok">';dynamic_sidebar('singleorta');echo '</div><div class="clear"></div>';}?>
			<!-- content  -->
			<div id="maviTmContent">
				<?php have_posts(); the_post(); $etiketler = get_the_tags(); ?>
				
				<?php
					echo get_post_format();
				?>
				
				
				<?php if(strlen(get_the_title()) > 60){$cHc = ' ek35';}else{$cHc = '';} ?>
				<div class="contentHead<?=$cHc;?>">	
				
					<h2 class="contentTitle"><?php the_title(); ?></h2>
					<div class="left postCat"><?php the_time('j F, Y'); ?> <?php the_time('g:ia'); ?></div>
					
									
					<div class="fontA">
						<?php if(get_option('MaviTmAddthisOn')){ ?>
						<a class="addthis_button left" href="http://www.addthis.com/bookmark.php?v=250&amp;username=mavitm" target="_blank"><img src="<?php bloginfo('template_directory');?>/images/share.png" alt="" title="" /> Payla&#351;</a>
						<?php } ?>
						<a class="left" id="yorumYap"><img src="<?php bloginfo('template_directory');?>/images/comments_add.png" alt="" title="" /> Yorum Yap</a>
						<a rel="up" class="fontFont left"><img src="<?php bloginfo('template_directory');?>/images/edit.png" alt="" title="" /> Font +</a>
						<a rel="down" class="fontFont right"><img src="<?php bloginfo('template_directory');?>/images/edit.png" alt="" title="" /> Font -</a>
					</div>
					
					<div class="clear"></div>
					
				</div>

				
				<div class="contentText fontUpDown">
					<?php the_content();?>
				</div>
	
			</div>
			
			
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
				
				<div class="nav-next"><?php next_post_link( '%link', __( 'Bir Sonraki Haber <span class="meta-nav">&raquo;</span>', 'twentyeleven' ) ); ?></div>
				<div class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&laquo;</span> Bir &Ouml;nceki Haber', 'twentyeleven' ) ); ?></div>
				
			</div>
			<div class="clear"></div>	
			
			<?php if(is_dynamic_sidebar('singlealt')){echo '<div class="ortaBlok">';dynamic_sidebar('singlealt');echo '</div><div class="clear"></div>';}?>
			
			<div class="clear"></div>	
			<div class="mrBottom10"></div>
				<?php if(get_option('MaviTmYorumTip') == 1){ ?>
						<ul class="contentTabUl" id="contentTabUl">
							<li><a>Di&#287;er Haberler</a></li>
							<li><a id="yorum">Yorumlar (<?php comments_number('0', '1', '%');?>)</a></li>
							<?php if(is_array($etiketler)){ ?><li><a>Aramalar</a></li><?php }?>
							<li><a>Yazd&#305;r</a></li>
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
							<div class="contentTabIc none">
								<ul class="classicUl">
									<li><a class="printYek" rel="content">&#304;lgili i&ccedil;eri&#287;i yazd&#305;r</a></li>
									<li><a class="printYek" rel="tumunuAl">Hepsini yazd&#305;r</a></li>
								</ul>
							</div>
						</div>
						<script type="text/javascript">pageTabs('contentTabUl','singleTabDis','contentTabIc')</script>
						
						<div id="digerCopy" class="none fullNone">
							<?php 
								$catTut = get_the_category();
								$maviTm->kategoridenDigerRba($catTut,$post->ID);
							?>
						</div>
						
				<?php }else{ ?>
					
					<ul class="classicUl">
						<li class="left"><a class="printYek" rel="content">&#304;lgili i&ccedil;eri&#287;i yazd&#305;r</a></li>
						<li class="right"><a class="printYek" rel="tumunuAl">Hepsini yazd&#305;r</a></li>
					</ul>
					<div class="clear"></div>	
					<div class="mrBottom10"></div>
					<div class="tabTitle"><h2>Yorumlar</h2></div>
					<?php comments_template( '', true );?>
					<div class="clear"></div>	
					<div class="mrBottom10"></div>
				<?php } ?>
			<div id="yorumForm"></div>	
			<?php if(is_dynamic_sidebar('singleyorumalt')){echo '<div class="ortaBlok">';dynamic_sidebar('singleyorumalt');echo '</div><div class="clear"></div>';}?>
	</div>
	
	<div class="sagAlan">
			<?php if(is_dynamic_sidebar('singlesag')){dynamic_sidebar('singlesag');}?>
	</div>
	
	<div class="clear"></div>
	
	<?php if(is_dynamic_sidebar('singleenalt')){echo '<div class="blokUst">';dynamic_sidebar('singleenalt');echo '</div>';}?>
	
	<div class="clear"></div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var buranAl = $("#digerCopy").html(); $("#digerHaberler").html(buranAl); $("#contentTabUl li:first a").click(); 
		$("#yorumYap").click(function(){ $("#yorum").click();	$.scrollTo( 'div#yorumForm', {duration:1000} ); });
	});
</script>
<?php get_footer(); ?>