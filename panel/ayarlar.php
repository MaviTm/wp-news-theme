<?php
function MaviTm_add_init() {
	$file_dir=get_bloginfo('template_directory');
	wp_enqueue_style("functions", $file_dir."/panel/panel.css", false, "1.0", "all");
	wp_enqueue_script("jq_script", $file_dir."/js/jquery.js", false, "1.7.2");
	wp_enqueue_script("MaviTm", $file_dir."/js/mavitm.js", false, "1.0");
}

function MaviTm_add_admin() {
	$veriler = MaviTmOptionGet();
	$options = $veriler[0];
	$kategoriler = $veriler[1];
	
	if ( $_GET['page'] == basename(__FILE__) ) {
		
	    if ($_POST['islem'] == 'save') {
	    	
			
			foreach ($options as $value) {
		   		if(isset($_REQUEST[$value['id']])) { 
		   				$yaz[$id] = $_REQUEST[$value['id']];
		   				update_option( $value['id'], $_REQUEST[$value['id']]); 
		   				$yaz[$id] = $_REQUEST[$value['id']];
		   		} else {delete_option( $value['id'] );} 
			}
			
			if(is_array($yaz)){
				update_option('MaviTmSettingAll', serialize($yaz)); 
			}
			
		    header("Location: admin.php?page=ayarlar.php&mesaj=oistamam");
		}
	 
		elseif($_POST['islem'] == 'reset') {
		    foreach ($options as $value) {
		        delete_option($value['id']); 
		    }
		    delete_option('MaviTmSettingAll'); 
		    header("Location: admin.php?page=ayarlar.php&mesaj=neyaptinsen");
		}
	}
 	
 	add_menu_page('MaviTm', 'MaviTm Ayarlar', 'edit_themes', basename(__FILE__), 'MaviTm_admin');
 
	
}

function MaviTm_admin(){
	switch (@$_GET['action']){
		case 'info': MaviTm_admin_info(); break;
		default: MaviTm_adminSettingSet();
	}
}

function MaviTm_adminSettingSet() {
	$veriler = MaviTmOptionGet();
	$options = $veriler[0];
	$kategoriler = $veriler[1];
	?>
		<div class="head">
			<div class="logo"></div>
		</div>
		<div class="menuler">
			<a href="admin.php?page=ayarlar.php" class="active">Ayarlar</a>
			<a href="admin.php?page=ayarlar.php&action=info">Bilgiler</a>
		</div>
		<div class="clear"></div>
		<?php 
			if(isset($_GET['mesaj'])){
				$kapanacakVar = 1;
				if($_GET['mesaj'] == 'oistamam'){
					?><div class="success sonrabunukapat">Ayarlar&#305;n&#305;z Kay&#305;t Edildi.</div><?php
				}elseif($_GET['mesaj'] == 'neyaptinsen'){
					?><div class="notice sonrabunukapat">Ayarlar&#305;n&#305;z Kald&#305;r&#305;ld&#305;.</div><?php
				}
			}
		?>
		
		<form method="POST" action="" id="MaviTmSaveForm">
			<ul id="adminTab">
				<li><a>Site Genel Ayarlar&#305;</a></li>
				<li><a>&#304;&ccedil;erik Ayarlar&#305;</a></li>
				<li><a>Kategori Ayarlar&#305;</a></li>
			</ul>
			<div id="tabArea">
							
				<div class="tabTab">
					<?php 
						$buAutoOlsun = array('MaviTmCssCompress','MaviTmLogo','MaviTmUstMenu','MaviTmAltMenu','MaviTmAddthisUser','MaviTmAddthisOn'); 
						PanelformuOlustur($buAutoOlsun);
					?>							
				</div>
				
				<div class="tabTab none">
					<?php 
						$buAutoOlsun = array('MaviTmOzelAlan','MaviTmHideManset','MaviTmYorumTip','MaviTmDigerHaberLimit','MaviTmAttachmentColorBox','MaviTmPlayerEn','MaviTmPlayerBoy','MaviTmPlayerAuto'); 
						PanelformuOlustur($buAutoOlsun);
					?>
				</div>
				
				<div class="tabTab none">
					<p class="info">Bu ayarlar Kategori, Ar&#351;iv ve Arama b&ouml;l&uuml;mlerinide etkiler.</p>
					<div class="hr"></div>
					<?php 
						$buAutoOlsun = array('MaviTmKategoriGorunum','MaviTmKatRightSidebar','MaviTmSayfaLimit','MaviTmKatBaslikLimit','MaviTmKatTextLimit'); 
						PanelformuOlustur($buAutoOlsun);
					?>
				</div>
				
			</div>
			<input type="hidden" name="islem" value="save" />
		</form>
		
		<div class="buttonArea">
			<input type="button" class="button-primary" id="MaviTmSaveFormSubmit" value="Ayarlar&#305; Kaydet" />
			<input type="button" class="button-primary" id="MaviTmSaveFormReset" value="T&uuml;m Ayarlar&#305; S&#305;f&#305;rla" />
		</div>
		
		<form method="POST" action="" id="MaviTmResetForm">
			<input type="hidden" name="islem" value="reset" />
		</form>

		<script type="text/javascript">
			$(document).ready(function(){
				$("#MaviTmSaveFormSubmit").click(function(){
					$("#MaviTmSaveForm").submit();
				});
				$("#MaviTmSaveFormReset").click(function(){
					var onaylattir = confirm ("Tum ayarlari sifirlamak istediginize eminmisiniz ?");
					if(onaylattir){$("#MaviTmResetForm").submit();}
				});
			});
			
			pageTabs('adminTab','tabArea','tabTab');
			<?php
				if(!empty($kapanacakVar)){?>
					setTimeout("myvyupremove();",3000)
					function myvyupremove(){$(".sonrabunukapat").slideUp("normal",function(){$(".sonrabunukapat").remove();});}
			<?php }
			?>
		</script>
	<?php
	
}

function MaviTm_admin_info(){
	?>
		<div class="head">
			<div class="logo"></div>
		</div>
		<div class="menuler">
			<a href="admin.php?page=ayarlar.php">Ayarlar</a>
			<a href="admin.php?page=ayarlar.php&action=info" class="active">Bilgiler</a>
		</div>
		<div class="clear"></div>
		<div class="boxTitle">
			<a class="icoSetting">MaviTm Bilgi Merkezi</a>
		</div>
		<div class="tMBox">
			
		</div>
	<?php
}

function MaviTmCatGet(){
	$kategoriler = get_categories( "hide_empty=0" );
	$returnCat = array( );
	foreach ($kategoriler as $kat){
		$returnCat[$kat->cat_ID] = $kat->cat_name.' ('.$kat->cat_ID.')';
	}
	return $returnCat;
}

function MaviTmOptionGet(){
	global $kategoriler;
	
	if(!is_array($kategoriler)){
		$kategoriler = MaviTmCatGet();
	}
	
	$returnOptions = array(
		
		######################//site
		'MaviTmCssCompress' => array("name" => "Css dosyalarını sıkıştır.",
			"desc" => "Css dosyalarınız tek dosya haline getirilir ve kullanıcı bilgisayarında hafızaya alınır. Dezavantajı css te yaptığınız değişiklik hemen yansımaz. Avantajı hız ve google için önemli",
			"id" => "MaviTmCssCompress",
			"icon" => "edit-diff.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "1"
			
		),
		'MaviTmLogo'=>array( "name" => "Site Logo",
			"desc" => "Logonuzun url veya dizin adresini belirtiniz.",
			"id" => "MaviTmLogo",
			"icon" => "image.png",
			"type" => "text",
			"std" => "wp-content/themes/maviTmHaber/images/logo.png"
		),
		'MaviTmUstMenu' => array("name" => "Site &uuml;st men&uuml;leri kullan",
			"desc" => "Hedader k&#305;sm&#305;ndaki kategorilerin &uuml;st taraf&#305;nda bulunan menu.",
			"id" => "MaviTmUstMenu",
			"icon" => "sitemap-application-blue.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "1"
			
		),
		'MaviTmAltMenu' => array("name" => "Site alt men&uuml;leri kullan",
			"desc" => "Footer k&#305;sm&#305;ndaki kategorilerin alt taraf&#305;nda bulunan menu.",
			"id" => "MaviTmAltMenu",
			"icon" => "sitemap-application-blue.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "1"
			
		),
		'MaviTmAddthisUser'=>array( "name" => "AddThis Kullan&#305;c&#305; ad&#305;",
			"desc" => 'Sosyal a&#287;larda payla&#351;&#305;m linkleri.<img src="'.get_bloginfo('template_directory').'/panel/images/bg-header-logo.gif" alt="" class="right mr5" />',
			"id" => "MaviTmAddthisUser",
			"icon" => "share.png",
			"type" => "text",
			"std" => "mavitm"
		),
		'MaviTmAddthisOn' => array("name" => "AddThis Aktif Olsunmu",
			"desc" => "Sosyal a&#287;larda payla&#351;&#305;m linkleri.",
			"id" => "MaviTmAddthisOn",
			"icon" => "share.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "1"
			
		),
		
		
		######################//icerik ayarlari
		'MaviTmOzelAlan'=>array( "name" => "&#304;&ccedil;eriklerinizde resim alan&#305; olarak kullan&#305;&#287;&#305;n&#305;z &ouml;zel alan ad&#305;",
			"desc" => "Genelde thumb, img, resim vs.. &#351;eklinde olur. Belirtmedi&#287;iniz takdirde teman&#305;n varsay&#305;lan&#305; thumb de&#287;eridir.",
			"id" => "MaviTmOzelAlan",
			"icon" => "slide.png",
			"type" => "text",
			"std" => "thumb"
		),
		'MaviTmHideManset' => array("name" => "Man&#351;ette g&ouml;r&uuml;len i&ccedil;erikleri sayfa i&ccedil;erisinde ba&#351;ka yerlerde g&ouml;sterme",
			"desc" => "Man&#351;et i&ccedil;erisinde gelen bir haber veya i&ccedil;eri&#287;i sitenin sa&#287;&#305;nda, solunda tekrar g&ouml;r&uuml;nt&uuml;lenmesini engeller. Man&#351;et &uuml;st&uuml;nde kalan widget pozisyonlar&#305; bundan faydalanamaz.",
			"id" => "MaviTmHideManset",
			"icon" => "application-dialog.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "0"
			
		),
		'MaviTmYorumTip' => array("name" => "Yorumlar&#305; kapal&#305; olarak g&ouml;ster",
			"desc" => "Yorum yap ba&#287;lant&#305;s&#305; g&ouml;r&uuml;n&uuml;r fakat yorumlar ve yorum formu linke t&#305;klanmad&#305;k&ccedil;a g&ouml;r&uuml;nmez.",
			"id" => "MaviTmYorumTip",
			"icon" => "balloon-white.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "1"
			
		),
		'MaviTmDigerHaberLimit'=>array( "name" => "Bir haber g&ouml;r&uuml;nt&uuml;lenirken di&#287;erleri ka&ccedil; adet g&ouml;r&uuml;ns&uuml;n",
			"desc" => "Haber ve eklerin alt&#305;nda g&ouml;r&uuml;nen k&#305;s&#305;mdaki veri say&#305;s&#305;.",
			"id" => "MaviTmDigerHaberLimit",
			"icon" => "application-dialog.png",
			"type" => "text",
			"std" => "10"
		),
		'MaviTmAttachmentColorBox'=>array( "name" => "Ekteki resimler i&ccedil;in color box kullan",
			"desc" => "Attachment k&#305;sm&#305;nda resimlerin &uuml;zerine t&#305;kland&#305;&#287;&#305;nda javascript ile resmi &ouml;n izlemeye sunar ve di&#287;er resimlere kolayca ge&ccedil;ilmesini sa&#287;lar.",
			"id" => "MaviTmAttachmentColorBox",
			"icon" => "image.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r (Resime t&#305;klad&#305;&#287;&#305;mda sayfa yeniden y&uuml;klenip bir sonraki resim gelsin)'),
			"std" => "1"
		),
		'MaviTmPlayerEn'=>array( "name" => "Video player eni",
			"desc" => "Attachment k&#305;sm&#305;ndaki video player eni .",
			"id" => "MaviTmPlayerEn",
			"icon" => "ruler.png",
			"type" => "text",
			"std" => "720"
		),
		'MaviTmPlayerBoy'=>array( "name" => "Video player boyu",
			"desc" => "Attachment k&#305;sm&#305;ndaki video player boyu.",
			"id" => "MaviTmPlayerBoy",
			"icon" => "ruler.png",
			"type" => "text",
			"std" => "300"
		),
		'MaviTmPlayerAuto' => array("name" => "Video ve ses dosyalar&#305;n&#305; otomatik olarak oynat",
			"desc" => "Bu ayar sadece attachment b&ouml;l&uuml;m&uuml;nde aktif olur sitenin di&#287;er k&#305;s&#305;mlar&#305;nda aktif olmaz.",
			"id" => "MaviTmPlayerAuto",
			"icon" => "film.png",
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "1"
			
		),
		
		########### Kategori Ayarlari
		'MaviTmKategoriGorunum' => array("name" => "Kategoriler ka&ccedil; s&uuml;tun olarak g&ouml;r&uuml;ns&uuml;n",
			"desc" => "Side barlar&#305; devre d&#305;&#351;&#305; b&#305;rakt&#305;&#287;&#305;n&#305;zda 2 -> 3, 3 -> 4 olabilir",
			"id" => "MaviTmKategoriGorunum",
			"icon" => 'ui-split-panel.png',
			"type" => "select",
			"options" => array(1=>'1 Sutun',2=>'2 S&uuml;tun',3=>'3 S&uuml;tun'),
			"std" => "2"
		),
		'MaviTmKatRightSidebar' => array("name" => "Kategorilere girildi&#287;inde sa&#287; sidebar&#305; kapat",
			"desc" => "Bu se&ccedil;ene&#287;i evet olarak ayarlad&#305;&#287;&#305;n&#305;zda kategori sa&#287; sidebar&#305; bile&#351;en y&ouml;netimindede g&ouml;r&uuml;nmez",
			"id" => "MaviTmKatRightSidebar",
			"icon" => 'ui-panel.png',
			"type" => "select",
			"options" => array(1=>'Evet',0=>'Hay&#305;r'),
			"std" => "0"
		),
		'MaviTmSayfaLimit' => array("name" => "Sayfalama numaralar&#305; k&#305;s&#305;tlamalar&#305;",
			"desc" => "&Ouml;rn: 5 dedi&#287;inizde 1,2,3,4,5 [Toplam Sayfa :1285, &#350;uan : 6] 7, 8, 9, 10, 11 Gibi sa&#287; ve solda 5 er tane b&#305;rak&#305;r",
			"id" => "MaviTmSayfaLimit",
			"icon" => 'sort-number.png',
			"type" => "select",
			"options" => array(3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10),
			"std" => "5"
		),
		'MaviTmKatBaslikLimit'=>array( "name" => "Liste ekran&#305;nda ba&#351;l&#305;klar&#305;n <b>kelime</b> say&#305;s&#305;",
			"desc" => "",
			"id" => "MaviTmKatBaslikLimit",
			"icon" => "sort-alphabet.png",
			"type" => "text",
			"std" => "3"
		),
		'MaviTmKatTextLimit'=>array( "name" => "Liste ekran&#305;nda a&ccedil;&#305;klamalar&#305;n <b>kelime</b> say&#305;s&#305;",
			"desc" => "",
			"id" => "MaviTmKatTextLimit",
			"icon" => "sort-alphabet.png",
			"type" => "text",
			"std" => "6"
		),
	);
	
	return array($returnOptions,$kategoriler);
}

function PanelformuOlustur($hangileri = false){
	$veriler = MaviTmOptionGet();
	if(!is_array($hangileri)){
		$dizi = $veriler[0];
	}else {
		foreach ($hangileri as $ne){
			$dizi[$ne] = $veriler[0][$ne]; 
		}
	}
	
	foreach ($dizi as $in=>$frm){
		switch ($frm['type']){
			case "text":
					?>
					<p>
						<label for="<?=$dizi[$in]['id'];?>"><img src="<?php bloginfo('template_directory');?>/panel/images/<?=$dizi[$in]['icon'] == true ? $dizi[$in]['icon'] : 'gear.png';?>" alt="" /><?php _e($dizi[$in]['name']); ?></label>
						<input class="widefat" id="<?php echo $dizi[$in]['id'] ?>" name="<?php echo $dizi[$in]['id'] ?>" type="text" value="<?php echo get_option($dizi[$in]['id']) == true ? get_option($dizi[$in]['id']) : $dizi[$in]['std']; ?>" />
						<i><?php _e($dizi[$in]['desc'].''); ?></i>
					</p>
					<div class="hr"></div>
					<?php
				break;
			case "select":
					?>
					<p>
						<label for="<?=$dizi[$in]['id'];?>"><img src="<?php bloginfo('template_directory');?>/panel/images/<?=$dizi[$in]['icon'] == true ? $dizi[$in]['icon'] : 'gear.png';?>" alt="" /><?php _e($dizi[$in]['name']); ?></label>
						<select class="widefat" id="<?php echo $dizi[$in]['id'] ?>" name="<?php echo $dizi[$in]['id'] ?>">
							<?php foreach ($dizi[$in]['options'] as $id => $name){?>
								<option <?php selected($id, get_option($dizi[$in]['id'])); ?> value='<?=$id;?>'><?=$name; ?></option>
							<?php }?>
						</select>
						<i><?php _e($dizi[$in]['desc']); ?></i>
					</p>
					<div class="hr"></div>	
					<?php
				break;
			case "textarea":
					?>
					<p>
						<label for="<?=$dizi[$in]['id'];?>">
							<img src="<?php bloginfo('template_directory');?>/panel/images/<?=$dizi[$in]['icon'] == true ? $dizi[$in]['icon'] : 'gear.png';?>" alt="" /><?php _e($dizi[$in]['name']); ?>
						</label>
						<textarea class="widefat" id="<?php echo $dizi[$in]['id'] ?>" name="<?php echo $dizi[$in]['id'] ?>"> <?php echo get_option($dizi[$in]['id']) == true ? get_option($dizi[$in]['id']) : $dizi[$in]['std']; ?></textarea>
						<i><?php _e($dizi[$in]['desc'].''); ?></i>
					</p>
					<div class="hr"></div>
					<?php
				break;
			//baska yok bukadar
		}
	}
}


add_action('admin_init', 'MaviTm_add_init');
add_action('admin_menu', 'MaviTm_add_admin');
?>