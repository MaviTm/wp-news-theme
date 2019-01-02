<?php
	class MaviTmLoads{
		
		/**
		 * isletim sistemlerine gore belirlenen dizin ayraci
		 *
		 */
		const DS = DIRECTORY_SEPARATOR;
			
		/**
		 * diger siniflar (istenilen sinif ilk once bu degiskenin icinde olup olmadigina bakilir)
		 *
		 * @var array
		 */
		private static $_instances = array();
		
		public $cache = null, $config = null;
				
		/**
		 * olusan dinamik ve gecici degerleri tekrar tekrar olusturmak yerine metodlar arasinda ulasabilmek icin
		 *
		 * @var array
		 */
		private $tasiyici = array(); 
		
		public $uzantilar = array(); //oynatabildigi uzantilar dizisi
		public $sunucu = array(); //gosterebildigi sunucular
		
		/************************************************************
		* *** get ve set metodlar
		************************************************************/
		
		public function tasiyiciGet($istek){
			if(empty($this->tasiyici[$istek])){ return null; }
			return $this->tasiyici[$istek];
		}
		
		//bu metodu silll kullanilmiyor hcbiryerde
		public function tasiyiciFullGet(){
			return $this->tasiyici;
		}
		
		public function tasiyiciSet($name,$val){
			if(empty($name)){ return ;}
			if(array_key_exists($name,$this->tasiyici['degismez'])){
				$this->tasiyici[$name.'s'] = $val;
			}else{
				$this->tasiyici[$name] = $val;
			}
		}
		
		public function __construct(){
			$this->tasiyici['degismez'] = array();
			$this->uzantilar = array("mpeg" => "mplayer","mpg" =>"mplayer", "3gp"=> "flv","wmv" => "mplayer","midi" => "mplayer","wma" => "mplayer","avi" => "divx","divx" => "divx","wav" => "mplayer","mp3" => "mp3","flv" => "flv","mp4" => "flv", "swf" => "swf");
			$this->sunucu = array("google" =>'google',"mynet" => "mynet",	"izlesene" => "izlesene",);
		}
		
		/**
		 * factory
		 *
		 * @param string $sinif
		 * @param array $param sinif parametreleri
		 * @return object
		 */
		public static function factory( $sinif, array $param = array() ){
			$p = false;
			$toplamParametre = count($param);
			
		 	if($toplamParametre > 1){
	        	ksort($param);
	        	$func = create_function('&$val', 'return (string) $val;');
	        	$param = array_map($func, $param);
	        	$paramS = array_fill(0,$toplamParametre,'%s');
	        	$olusacakParametre = implode(',',$paramS);
	        	$p = true;
	        }

			//sinif daha onceden basladiysa onu donder
			if (array_key_exists($sinif, self::$_instances) === true) {
				//eger duzen olmuyorsa geri donmede parametreleri vermedigim icin olabilir bu satirda takla atmam gerekebilir
	            return self::$_instances[$sinif];
	        }
	        
	        if(!class_exists($sinif)){
	        	if(file_exists(dirname(__FILE__).MaviTmLoads::DS.$sinif.'.php') && is_file(dirname(__FILE__).MaviTmLoads::DS.$sinif.'.php')){
	        		include_once(dirname(__FILE__).MaviTmLoads::DS.$sinif.'.php');
	        	}else{
	        		return ;
	        	}
	        }
	        
			if($p == true){
        		$string = '$start = new $sinif('.vsprintf($olusacakParametre,$param).');';
        		@eval($string);
        	}elseif ($toplamParametre == 1){
        		$start = new $sinif($param[0]);
        	}else{
        		$start = new $sinif();
        	}
			
        	self::$_instances[$sinif] = $start;
        	return $start;
		}
		
		/******** WIDGETS ******/
		public function digerResimlerBr($catTut,$notIn = false){
			include_once(get_template_directory().'/view/br.php');
			$query_args['posts_per_page'] = get_option('MaviTmDigerHaberLimit') > 0 ? get_option('MaviTmDigerHaberLimit') : 10;
			$query_args['order'] = 'DESC';
			$query_args['orderby'] = 'date';
			if($notIn != false){
				$query_args['post__not_in'] = array($notIn);
			}
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array("post-format-gallery"),
					'operator' => 'IN'
				)	
			);
			$query = new WP_query($query_args);
			?>
				<div class="maviTmPost widgetTab">
					<?php maviTmViewbr(array('titleLimit'=>4,'tetxLimit'=>10),$query);?>
				</div>
				<div class="clear"></div>
				<div class="mrBottom10"></div>
			<?php
		}
		public function digerVideolarBr($catTut,$notIn = false){
			include_once(get_template_directory().'/view/br.php');
			$query_args['posts_per_page'] = get_option('MaviTmDigerHaberLimit') > 0 ? get_option('MaviTmDigerHaberLimit') : 10;
			$query_args['order'] = 'DESC';
			$query_args['orderby'] = 'date';
			if($notIn != false){
				$query_args['post__not_in'] = array($notIn);
			}
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array("post-format-video"),
					'operator' => 'IN'
				)	
			);
			$query = new WP_query($query_args);
			?>
				<div class="maviTmPost widgetTab">
					<?php maviTmViewbr(array('titleLimit'=>4,'tetxLimit'=>10),$query);?>
				</div>
				<div class="clear"></div>
				<div class="mrBottom10"></div>
			<?php
		}
		public function kategoridenDigerRba($catTut,$notIn = false){
			include_once(get_template_directory().'/view/rba.php');
			foreach ($catTut as $tut){
				
				$query_args['posts_per_page'] = get_option('MaviTmDigerHaberLimit') > 0 ? get_option('MaviTmDigerHaberLimit') : 10;
				$query_args['order'] = 'DESC';
				$query_args['orderby'] = 'date';
				$query_args['cat'] = $tut->cat_ID;
				if($notIn != false){
					$query_args['post__not_in'] = array($notIn);
				}
				$query = new WP_query($query_args);
			?>
			<div class="tabTitle"><h2><?=$tut->name;?></h2></div>
			<div class="maviTmPost widgetTab">
				<?php maviTmViewrba(array('titleLimit'=>4,'tetxLimit'=>10),$query);?>
			</div>
			<a href="<?=get_category_link($tut->cat_ID);?>" title="<?=$tut->name;?>" class="right pButton"><?=$tut->name;?> T&uuml;m haberleri</a>
			<div class="clear"></div>
			<div class="mrBottom10"></div>
			<?php }
		}
		/**********************/
		
		/****** IMAGES *****/
		public function mavitmThumb($css){
			if(has_post_thumbnail()) {
					$return = get_the_post_thumbnail(
						get_the_ID(), 
						'thumbnail', 
						array(
							'class' => $css,
							'title'=>the_title_attribute('echo=0')
						)
					);
					return $return;
			}elseif (get_post_meta(get_the_ID(), OZELALAN, true) == true){
				if(strlen(get_post_meta(get_the_ID(), OZELALAN, true)) > 10){
					return  '<img src="'.get_post_meta(get_the_ID(), OZELALAN, true).'" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="'.$css.'" />';
				}
			}
			return '<img src="'.get_bloginfo("template_url").'/images/resimyok.jpg" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="'.$css.'" />';
		}
		public function MaviTmMansetResimBul(){
			$return = array();
			if(has_post_thumbnail()) {
					$return['buyuk'] = get_the_post_thumbnail(get_the_ID(), array(480,300), array('class' => 'mansetResim','title'=>the_title_attribute('echo=0')));
					$return['kucuk'] = get_the_post_thumbnail(get_the_ID(), array(60,35), array('class' => 'mansetResim','title'=>the_title_attribute('echo=0')));
					return $return;
			}elseif (get_post_meta(get_the_ID(), OZELALAN, true) == true){
				if(strlen(get_post_meta(get_the_ID(), OZELALAN, true)) > 10){
					$return['buyuk'] = '<img src="'.get_post_meta(get_the_ID(), OZELALAN, true).'" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="mansetResim" />';	
					$return['kucuk'] = '<img src="'.get_post_meta(get_the_ID(), OZELALAN, true).'" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="mansetResim" />';	
					return $return;
				}
			}else{
					$content = get_the_content();
					if (preg_match('/\< *[img][^\>]*[src] *= *[\"\']{0,1}([^\"\']*)/i',$content,$ver)){
						if(strlen($ver[1]) > 10){
							$return['buyuk'] = '<img src="'.$ver[1].'" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="mansetResim" />';
							$return['kucuk'] = '<img src="'.$ver[1].'" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="mansetResim" />';
							return $return;
						}
					}	
			}
			$return['buyuk'] = '<img src="'.get_bloginfo("template_url").'/images/resimyok.jpg" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="mansetResim" />';
			$return['kucuk'] = '<img src="'.get_bloginfo("template_url").'/images/resimyok.jpg" alt="'.get_the_title().'" title="'.the_title_attribute('echo=0').'" class="mansetResim" />';
			return $return;
		}
		/*******************/
		
		/***** MULTIMEDIA *****/
		public function vayaral($tur, $yol, $en, $boy, $autos, $dogru, $res){
			$this->url = $yol;	$this->ven = $en;	$this->vboy = $boy;		$this->oynat = $autos;		$this->onay = $dogru;		$this->img = $res;		
			$this->vturu = $this->uzanti_bak($tur,$yol);
		}
		protected function uzanti_bak($tur,$dosya){
				$filename = explode('.', $dosya);
				$uzanti = end($filename);
				
				if(array_key_exists($uzanti,$this->uzantilar)){
					$uzn = $this->uzantilar[$uzanti]; 
					$vid = 0; 
					return $uzn;
				}
				else{
					$tut = parse_url($dosya);
					$site = explode('.',$tut['host']);
					$sunucu = $site[count($site) - 2];
					if(array_key_exists($sunucu,$this->sunucu)){
						$uzn = $sunucu;
						$vid = 1;
					}
				}
				
				$videos = array(
						"google" => array(
							'#http://video\.google\.(com|com?\.[a-z]{2}|[a-z]{2})/(?:videoplay|url|googleplayer\.swf)\?[^"]*?docid=([\w-]{1,20})#i',
							'http://video.google.\1/googleplayer.swf?docId=\2',
							'<embed wmode="transparent" width="'.$this->ven.'" height="'.$this->vboy.'" id="VideoPlayback" type="application/x-shockwave-flash" src="'.$this->url.'&autoPlay='.$this->onay.'"  allowscriptaccess="sameDomain"/></embed>'	
						),
						/*
						"dailymotion" => array(
							'#http://(?:www\.)?dailymotion\.(?:com|alice\.it)/(?:[^"]*?video|swf)/([a-z0-9]*?)#i',
							'http://www.dailymotion.com/swf/\4',
							'<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="wmode" value="transparent"><param name="movie" value="'.$this->url.'&related=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="'.$this->url.'" type="application/x-shockwave-flash" width="'.$this->ven.'" height="'.$this->vboy.'" wmode="transparent" allowFullScreen="true" allowScriptAccess="always"></embed></object>'
						),
						"metacafe" => array(
							'#http://(?:www\.)?metacafe\.com/(?:watch|fplayer)/(.*?)/(.*?)(swf|/|$)#i',
							'http://www.metacafe.com/fplayer/$1/metacafe.swf',
							'<embed src="'.$this->url.'" width="'.$this->ven.'" height="'.$this->vboy.'" wmode="transparent" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowFullScreen="true"> </embed>'
						),
						*/
						"mynet" => array(
							'#http://video.mynet.com/([a-zA-Z0-9_\-\.]*?)/([a-zA-Z0-9_\-\.]*?)/([0-9]{1,20})/#i',
							'http://video.eksenim.mynet.com/batch/video_xml_embed.php?video_id=$3',
							'<embed wmode="transparent" src="http://video.eksenim.mynet.com/flvplayers/vplayer6.swf" type="application/x-shockwave-flash" wmode="transparent" FlashVars="videolist='.$this->url.'" allowfullscreen="true" width="'.$this->ven.'" height="'.$this->vboy.'" />'
						),
						"izlesene" => array(
							'#http://(?:www\.)?izlesene\.com/(?:player2\.swf\?video=|embedplayer\.swf\?video=|video/[\w-_]*?/)(\d{1,10})#i',
							'http://www.izlesene.com/embedplayer.swf?video=$1',
							'<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'.$this->url.'" /><embed src="'.$this->url.'" wmode="window" bgcolor="#000000" allowfullscreen="true" allowscriptaccess="always" menu="false" scale="noScale" width="'.$this->ven.'" height="'.$this->vboy.'" type="application/x-shockwave-flash"></embed></object>'
						
						),
						/*
						"sevenload" => array(
							'#http://((?:en|tr|de|www)\.)?sevenload\.com/(?:videos|videolar|yayinlar)/(.*?)/(.*?)/(.*?)-(.*?)$#i',
							'http://tr.sevenload.com/pl/$4',
							'<script type="text/javascript" src="'.$this->url.'/'.$this->ven.'x'.$this->vboy.'/0"></script>'
						),
						"youtube" => array(
							'#http://(?:www\.)?youtube.com/(watch\?v=|v/)(.*?)#i',
							'http://www.youtube.com/v/\3',
						),
						"yahoo" => array(
							'#http://video.yahoo.com/watch/([0-9]{6,12})/([0-9]{6,12})#i',
							'id=$2&vid=$1',
						),
						"megavideo" => array(
							//http://megavideo.com/?v=W23G6PA2
							'#http://((?:en|tr|de|www)\.)?megavideo\.com/\?v=(.*?)$#i',
							'http://www.megavideo.com/v/$2',
							'<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="movie" value="'.$this->url.'"></param><param name="allowFullScreen" value="true"></param><embed src="'.$this->url.'" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$this->ven.'" height="'.$this->vboy.'"></embed></object>'
						),
						"vimeo" => array(
							//gelen : http://vimeo.com/15783707
							//olmasi gereken : http://player.vimeo.com/video/15783707
							'#http://((?:www|de)\.)?vimeo\.com/(.*?)#i',
							'http://player.vimeo.com/video/$2',
						),
						*/			
				);
				
				if($vid == 1){
					$sablon = $videos[$uzn][0]; $yeni = $videos[$uzn][1];	
					if(preg_replace($sablon, $yeni, $dosya)) {$this->url = preg_replace($sablon, $yeni, $dosya); return $uzn;}	
				}
				if(!$uzn){return $tur;}
		}//fonksiyon
		public function videover(){
			$embed = array(
				"flv" => '<embed src="'.get_bloginfo("template_url").'/swf/player.swf" wmode="transparent" width="'.$this->ven.'" height="'.$this->vboy.'" allowscriptaccess="always" allowfullscreen="true" flashvars="height='.$this->vboy.'&width='.$this->ven.'&file='.$this->url.'&frontcolor=0xFFFFFF&lightcolor=cc9900&skin='.get_bloginfo("template_url").'/swf/skin2.swf&image='.$this->img.'&controlbar=over&autostart='.$this->onay.'"/>',
				"mp3" => '<embed src="'.get_bloginfo("template_url").'/swf/mediaplayer.swf" wmode="transparent" allowfullscreen="true" flashvars="&file='.$this->url.'&height=80&width='.$this->ven.'&autostart='.$this->onay.'&showeq=true" width="'.$this->ven.'" height="80">',
				"mplayer" => '<object id="mediaPlayer" width="'.$this->ven.'" height="'.$this->vboy.'" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Haber.50tr video yukleniyor" type="application/x-oleobject" /><param name="fileName" value="'.$this->url.'" /><param name="animationatStart" value="true" /><param name="transparentatStart" value="true" /><param name="autoStart" value="'.$this->onay.'" /><param name="showControls" value="true" /><param name="loop" value="false" /><param name="showstatusbar" value="true" /></object><object><embed type="application/x-mplayer2" pluginspage="http://microsoft.com/windows/mediaplayer/en/download/" id="mediaPlayer" name="mediaPlayer" showcontrols="1" showdisplay="0" showstatusbar="1" width="'.$this->ven.'" height="'.$this->vboy.'" src="'.$this->url.'" autostart="'.$this->oynat.'" /></embed></object>',
				"divx" => '<object codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab" height="'.$this->vboy.'" width="'.$this->ven.'" classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616"><param name="autoplay" value="'.$this->oynat.'"><param name="src" value="'.$this->url.'" /><param name="custommode" value="mavitm" /><param name="showpostplaybackad" value="'.$this->onay.'" /><embed type="video/divx" src="'.$this->url.'" pluginspage="http://go.divx.com/plugin/download/" showpostplaybackad="false" custommode="Stage6" autoplay="'.$this->onay.'" height="'.$this->vboy.'" width="'.$this->ven.'" /></object>',
				"youtube" => '<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="movie" value="'.$this->url.'?fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'.$this->url.'?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$this->ven.'" height="'.$this->vboy.'"></embed></object>',
				"google" => '<embed wmode="transparent" width="'.$this->ven.'" height="'.$this->vboy.'" id="VideoPlayback" type="application/x-shockwave-flash" src="'.$this->url.'&autoPlay='.$this->onay.'"  allowscriptaccess="sameDomain"/></embed>',
				"mynet2" => '<embed src=\''.$this->url.'\' type=\'application/x-shockwave-flash\' wmode=\'transparent\' allowfullscreen=\'true\'width=\''.$this->ven.'\' height=\''.$this->vboy.'\'></embed>',
				"mynet" => '<embed wmode="transparent" src="http://video.eksenim.mynet.com/flvplayers/vplayer6.swf" type="application/x-shockwave-flash" wmode="transparent" FlashVars="videolist='.$this->url.'" allowfullscreen="true" width="'.$this->ven.'" height="'.$this->vboy.'" />',
				"swf" => '<embed src="'.$this->url.'" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="bilenbilisim" width="'.$this->ven.'" height="'.$this->vboy.'" quality="High" wmode="transparent">',
				"dailymotion" =>'<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="wmode" value="transparent"><param name="movie" value="'.$this->url.''.($this->onay == true ? '&autoPlay=1' : '').'&related=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="'.$this->url.''.($this->onay == true ? '&autoPlay=1' : '').'" type="application/x-shockwave-flash" width="'.$this->ven.'" height="'.$this->vboy.'" wmode="transparent" allowFullScreen="true" allowScriptAccess="always"></embed></object>',
				"metacafe" => '<embed src="'.$this->url.'" width="'.$this->ven.'" height="'.$this->vboy.'" wmode="transparent" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowFullScreen="true"> </embed>',
				"yahoo" => '<embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.46" type="application/x-shockwave-flash" width="'.$this->ven.'" height="'.$this->vboy.'" wmode="transparent" allowFullScreen="true" AllowScriptAccess="always" bgcolor="#000000" flashVars="'.$this->url.'&lang=en-us&intl=us&thumbUrl='.$this->img.'&embed=1" ></embed>',
				"izlesene" => '<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'.$this->url.'" /><embed src="'.$this->url.'" wmode="window" bgcolor="#000000" allowfullscreen="true" allowscriptaccess="always" menu="false" scale="noScale" width="'.$this->ven.'" height="'.$this->vboy.'" type="application/x-shockwave-flash"></embed></object>',
				"sevenload" => '<script type="text/javascript" src="'.$this->url.'/'.$this->ven.'x'.$this->vboy.'/0"></script>',
				"megavideo" => '<object width="'.$this->ven.'" height="'.$this->vboy.'"><param name="movie" value="'.$this->url.'"></param><param name="allowFullScreen" value="true"></param><embed src="'.$this->url.'" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$this->ven.'" height="'.$this->vboy.'"></embed></object>',
				"vimeo" => '<iframe src="'.$this->url.'" width="'.$this->ven.'" height="'.$this->vboy.'" frameborder="0" scrolling="no" name="player"></iframe>',	
				"embed" => ''.stripslashes($this->url).''
			);
			if($embed[$this->vturu]){
				return $embed[$this->vturu];	
			}else {
				return $embed['embed'];
			}
		}

	}
	
	
function karakterKirp($string, $word_limit){$string = strip_tags($string); $words = explode(' ', $string, ($word_limit + 1)); if(count($words) > $word_limit){ array_pop($words); return implode(' ', $words)."..."; }else { return implode(' ', $words); }}
function maviTmSayfalama($ustHtml,$altHtml) {
	global $wp_query;
	$endSize = get_option('MaviTmSayfaLimit') > 0 ? get_option('MaviTmSayfaLimit') : 5;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$pageNum = array('base' => @add_query_arg('paged','%#%'),'total' => $wp_query->max_num_pages,'current' => $current,'end_size' => $endSize, 'mid_size' => ($endSize+1));
	echo $ustHtml.paginate_links($pageNum).$altHtml;
}
?>