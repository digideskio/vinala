<?php 
	use Lighty\Kernel\Resources\Libs;
	use Lighty\Kernel\HyperText\Html;
	use Lighty\Kernel\Access\Path;
	use Lighty\Kernel\Config\Config;
	use Lighty\Kernel\Translator\Lang;
?>

<div class="bg" id="bg"></div>
<div class="content" id="content">

	<script type="text/javascript">

		fade0();
		//
		var Timer3 = setInterval(function(){ fade3() }, 500);
		var Timer1 = setInterval(function(){ fade1() }, 600);
		var Timer2 = setInterval(function(){ fade2() }, 1000);

		function fade0 () 
		{
			$( "#content" ).fadeTo( "slow", 1 );
			//
			$({blurRadius: 0}).animate({blurRadius: 5}, {
		        duration: 600,
		        easing: 'linear', // "swing"
		        //
		        step: function() {
		            console.log(this.blurRadius);
		            $('#bg').css({
		                "-webkit-filter": "blur("+this.blurRadius+"px)",
		                "filter": "blur("+this.blurRadius+"px)"
		            });
		        }
		    });
		}

		function fade1 () 
		{
			$( "#welcom" ).fadeTo( "slow", 1 );
			clearInterval(Timer1);
		}

		function fade2 () 
		{
			$( "#bottom_panel" ).fadeTo( "slow", 1 );
			$( "#bottom_panel_2" ).fadeTo( "slow", 1 );
			clearInterval(Timer2);
		}

		function fade3 () 
		{
			$( "#hello_logo" ).fadeTo( "slow", 1 );
			clearInterval(Timer3);
		}
	</script>

	<img src="<?php echo "app/resources/images/pikia_logo.png" ?>" class="img2" id="hello_logo" style="display:none">

	<div id="welcom" style="display:none">

		<div class="text">
			<?php 
				echo Lang::get('welcome');
				//
				if(Base::full(Config::get('app.owner'))) 
					if(Config::get('app.owner') != "user")
						echo " <span id='dev_nom'>".Config::get('app.owner')."</span>";
			?>
		</div>
	</div>

	<div class="bottom_panel bottom_panel_2" id="bottom_panel_2" style="display:none">
		<?php echo "v ".App::fullVersion(); ?>
	</div>
	<div class="bottom_panel" id="bottom_panel" style="display:none">
		<a id="fst_panel" href="<?php echo Config::get("panel.route") ?>"><div class="btn hello_button" id="login">Pikia Panel</div></a>
	</div>
</div>