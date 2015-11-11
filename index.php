<?php
/*
	Plugin Name: YouTube Feed Shortcode
	Version: 15Sep15
	Author: D4 Adv. Media
	License: GPL2
*/


function register_youtubefeed_script() {

	wp_register_script( 'youtubefeed', plugins_url( '/js/youtubefeed.js' , __FILE__ ), array( 'jcycle2' ), '15Sep15', true );
	
} add_action( 'wp_enqueue_scripts', 'register_youtubefeed_script' );


function shortcode_youtubefeed( $atts ) {

	wp_enqueue_script('youtubefeed');

	$attr = shortcode_atts( array(
		'list' => '',
		'apikey' => '',
		'show' => '-1',
		'containerclass' => '',
		'thumbclass' => '',
		'frameclass' => ''
	), $atts );	

	$playlistID = $attr['list']; 
	$apiKey = $attr['apikey']; 	



	$cont = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='.$playlistID.'&key='.$apiKey )); 
	$feed = $cont->items;


	$output = '<div id="youtubefeed-'.$playlistID.'" class="chunk-youtubefeed">';
		// $output .= '<a href="https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='.$playlistID.'&key='.$apiKey .'" target="_blank">Link</a>';

		if(count($feed)){

			# shuffle ( $feed );
			# $output .= '<pre style="display:none;">'. print_r($feed, true) . '</pre>';


			if ( $attr['show'] != '-1' ) {
				$feed = array_slice($feed, 0, $attr['show']);
			}

			$i = 0;
			foreach($feed as $item){
				if ($item->snippet->thumbnails->medium->url != '') {

					$vidtitle = $item->snippet->title;
					$vidID    = $item->snippet->resourceId->videoId;
					$vidThumb = $item->snippet->thumbnails->medium->url;

					$src .= 'https://www.youtube.com/embed/'.$vidID.'?rel=0';



					$frameclass = '';
					if ( $attr['frameclass'] != '' ) {
						$frameclass = ' class="' . $attr['frameclass'] . '"';
					}

					$containerclass = '';
					if ( $attr['containerclass'] != '' ) {
						$containerclass = ' class="' . $attr['containerclass'] . '"';
					}

					$thumbclass = '';
					if ( $attr['thumbclass'] != '' ) {
						$thumbclass = ' class="' . $attr['thumbclass'] . '"';
					}

					if ( $i == 0 ) {
						$output .= '<div id="youtube-frame"' . $frameclass . '>';
							$output .= '<iframe width="100%" height="100%" frameborder="0" allowfullscreen src="'.$src.'"></iframe>';
						$output .= '</div>';
						$output .= '<div id="youtube-thumbs"' . $containerclass . '>'; 
						$thumbclass = 'youtube-active ' . $thumbclass;
					}


					$linkguts  = ' data-vidsrc="' . $vidID . '"';
					$linkguts .= ' href="'. $src . '"';
					$linkguts .= ' target="_blank"';
					$linkguts .= ' title="'. $vidtitle . '"';
					$linkguts .= ' style="background-image:url(' . $vidThumb . ');"';
					$linkguts .= ' class="' . $thumbclass . '"';

						//if ($i === 2) { $output .= '<div class="clear"></div>'; }
						$vidtitle = $item->snippet->title;
					/*	if ( strlen($vidtitle) > 35 ) {
							$vidtitle = substr($vidtitle,0,33).'...';
						} //*/
						$output .= '<a'. $linkguts .'>';
							$output .= '<span>'.$vidtitle . '</span>';
						$output .= '</a> ';
					
					$i++;
				}
			}	
		}
			
		$output .= '</div>';
	$output .= '</div>';


	return $output;
} add_shortcode( 'youtubefeed', 'shortcode_youtubefeed' );


?>