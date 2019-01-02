<?php
	$maviTm =& MaviTmLoads::factory('MaviTmLoads');
	$ulstyle = $maviTm->tasiyiciGet('mansetUl');$mansetSpeed = $maviTm->tasiyiciGet('mansetSpeed');	if(!empty($ulstyle)){?><script type="text/javascript">$(function(){	<?php if(is_array($ulstyle)){foreach ($ulstyle as $in=>$uls){?>$('.<?=$uls;?>').maviTmManset({mansetTime:<?=$mansetSpeed[$in] < 2000 ? 8000 : $mansetSpeed[$in];?>});<?php	}}else{	?>$('.<?=$ulstyle;?>').maviTmManset({mansetTime:<?=$mansetSpeed < 2000 ? 8000 : $mansetSpeed;?>});<?php	}?>	});	</script><?php	}  		
	$kaykay = $maviTm->tasiyiciGet('kaykay'); if(!empty($kaykay)){?>
	<script type="text/javascript">
		$(function(){
			<?php foreach ($kaykay as $value) {?>
				$(".<?=$value['bunauygula'];?>").mavitmCarusel({
					'ileriButton' : '.<?=$value['bunauygula'];?>Left',
					'geriButton' : '.<?=$value['bunauygula'];?>Right',
					'akistipi' : '<?=$value['akistipi'];?>', 
					'otomatik' : <?=$value['otomatik'] == 1 ? 'true' : 'false';?>,
					'otomatikKapat' : <?=$value['otomatikKapat'] == 1 ? 'true' : 'false';?>,
					'duraklamaSn' : <?=$value['duraklamaSn'] < 1 ? 2000 : $value['duraklamaSn'];?>,
					'gecisSn'  : <?=$value['gecisSn'] < 1 ? 400 : $value['gecisSn'];?>,
					'netarafa' : <?=$value['netarafa'] == 1 ? 1 : 0;?>
				});
			<?php }?>
		});
	</script>	
<?php }?>	
		<div id="footer">
			<div class="clear"></div>
			<div id="altKategori" class="titlekirmizi" role="navigation">
				<?php wp_nav_menu(array('theme_location' => 'primary3')); ?>
			</div>
			<div class="clear"></div>
			<div id="altMenu" role="navigation">
				<?php wp_nav_menu(array('theme_location' => 'primary4')); ?>
			</div>
			<div class="clear"></div>
			<div class="siteAltName"><?php the_time('Y'); ?> <?php bloginfo('name'); ?></div>
			<div class="clear"></div>
			
		</div>
		<?php wp_footer(); //ust baglantilar icin aktif et ?>
	</body>
</html>