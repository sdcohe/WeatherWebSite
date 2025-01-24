<!-- start #sidebar -->
<div id="sidebar">
	<ul>
		<li>
			<h2>Web Cam</h2>
			<ul>
				<li><a href="javascript:window.open('http://webcam.cloppermillweather.org/webcam','camwindow','width=825,height=625,toolbar=no,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes'); void('');" >
						<img src="<?php print $cfg->getWebcamImageSource()?>" width="220" height="165" id="webcamThumbnail" alt="Webcam image" title="Live Web Cam"/>
					</a>
					<span class="subli">Live Weather</span>
				</li>
				<li><a href="javascript:window.open('http://www.cloppermillweather.org/images/time-lapse.mp4?' + new Date(),'videowindow','width=825,height=625,toolbar=no,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes'); void('');" >
						Time Lapse Video From Yesterday
					</a>
				</li>
							</ul>
		</li>
		<li>
			<h2>Station Data</h2>
			<ul>
				<?php
				// dynamically build the sidebar navigation
				$currentPage = basename($_SERVER['SCRIPT_NAME']);
			
				// list of navigable pages
				$items = array(
					array('link'=>'stats.php', 'title'=> 'Cumulative Statistics', 'label'=>'Statistics'),
					array('link'=>'graphs.php', 'title'=> '24 and 48 Hour Graphs', 'label'=>'Graphs'),
					array('link'=>'history.php', 'title'=> 'Tabular data for the last 24 hours', 'label'=>'History'),
				);
			
				// loop through the list of items and build the navigation menu
				foreach($items as $item)
				{
					print "<li>";
					// disable link to current page, as user is already there
					if ($currentPage == $item['link'])
					{
						print "<span class=\"disabled\">" . $item['label'] . "</span>\n";
					}
					else
					{
						print '<a href="' . $item['link'] . '" title="' . $item['title'] . '">' . $item['label'] . "</a>\n";
							
					}
					print "<span class=\"subli\">" . $item['title'] . "</span>\n";
					print "</li>";
				}
				?>
			</ul>
		<li>
			<h2>Links</h2>
			<ul>
				<li><a href="http://weather.gladstonefamily.net/site/AS787">Citizen's
						Weather Observer Program</a><span class="subli">Station ID AS787</span>
				</li>
				
				<li><a
					href="https://www.wunderground.com/weather/us/md/germantown/KMDGERMA9">Weather
						Underground</a><span class="subli">Station ID KMDGERMA9</span></li>
<!--
				<li><a
					href="http://weather.weatherbug.com/MD/Germantown-weather.html?zcode=z6286&amp;stat=p15087">Weather Bug</a><span class="subli">Station ID P15087</span>
				</li>
-->
				<li><a href="http://mesowest.utah.edu/cgi-bin/droman/meso_base_dyn.cgi?stn=as787">MesoWest</a>
				<span class="subli">Station ID AS787</span></li>
				
				<li><a href="http://www.findu.com/cgi-bin/wxpage.cgi?call=kb3hha-13">FindU</a>
				<span class="subli">Station ID KB3HHA-13</span></li>
				
				<li><a href="http://www.pwsweather.com/obs/AS787.html">PWS
						Weather/Weather For You</a><span class="subli">Station ID AS787</span>
				</li>
				
				<li><a href="https://www.anythingweather.com/weather-network/?stationid=31018">Anything
						Weather</a><span class="subli">Station ID 31018</span>
				</li>
				
				<li><a
					href="http://www.awekas.at/en/instrument.php?id=8827&amp;tempeh=f">AWEKAS</a><span class="subli">Station ID 8827</span>
				</li>
				
			</ul></li>
	</ul>
</div>
<!-- end #sidebar -->