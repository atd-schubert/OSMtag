<?php
/*
 * OSM Plugin for CMS Made Simple
 * Author: Stefano Sabatini
 * Last rev: 22/08/2013
*/
function smarty_cms_function_osm($params, &$smarty)
{
    $div_width   = isset($params['w']) ? $params['w'] : 500;
  $div_height   = isset($params['h']) ? $params['h'] : 500;

    $zoom   = isset($params['z']) ? $params['z'] : 13;
	$center_lon   = isset($params['clon']) ? $params['clon'] : (isset($params['mlon']) ? $params['mlon'] : "");
	$center_lat   = isset($params['clat']) ? $params['clat'] : (isset($params['mlat']) ? $params['mlat'] : "");

	$single_marker_lon   = isset($params['mlon']) ? $params['mlon'] : "";
	$single_marker_lat   = isset($params['mlat']) ? $params['mlat'] : "";
	$single_marker_text   = isset($params['mtxt']) ? $params['mtxt'] : "";

	$gpx = isset($params['gpx']) ? $params['gpx'] : "";

	$mimg = isset($params['mimg']) ? $params['mimg'] : "http://www.openstreetmap.org/assets/images/marker-icon.png";

        $size   = isset($params['size']) ? $params['size'] : 13;
        $offset = isset($params['offset']) ? $params['offset'] : 13;

	$text_marker = isset($params['markerfile']) ? $params['markerfile'] : "";
?>

<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
    <style>
    div.olControlAttribution { bottom:3px; }
    </style>
<div id="mapdiv" style="width:<?php echo $div_width;?>px;height:<?php echo $div_height;?>px;"></div>  

  <script>
        map = new OpenLayers.Map("mapdiv");
        var mapnik = new OpenLayers.Layer.OSM();
        map.addLayer(mapnik);

		var clonlat = new OpenLayers.LonLat(<?php echo $center_lon;?>, <?php echo $center_lat;?>).transform( new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913") );
        var zoom = <?php echo $zoom;?>;
        map.setCenter(clonlat, zoom);
<?php
if (isset($gpx)&&$gpx!="") {
?>
	var lgpx = new OpenLayers.Layer.Vector("Percorso", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "<?php echo($gpx);?>",
			format: new OpenLayers.Format.GPX()
		}),
		style: {strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5},
		projection: new OpenLayers.Projection("EPSG:4326")
	});
	map.addLayer(lgpx);
 <?php } ?>

<?php
if (isset($single_marker_lon) &&isset($single_marker_lat) && $single_marker_lon !="" && $single_marker_lat !="" ) {
?>
        var mlonlat = new OpenLayers.LonLat(<?php echo $single_marker_lon;?>, <?php echo $single_marker_lat;?>).transform( new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913") );

        var markers = new OpenLayers.Layer.Markers( "Markers" );
        var icon =    new OpenLayers.Icon("<?php echo $mimg;?>", new OpenLayers.Size(25,41));
        
        map.addLayer(markers);

        


		AutoSizeFramedCloud = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
				    'autoSize': true
				});
            popupClass = AutoSizeFramedCloud;
            popupContentHTML = '<?php if (isset($single_marker_text)) echo $single_marker_text;?>';
            addMarker(mlonlat, popupClass, popupContentHTML, true, true);

      function addMarker(ll, popupClass, popupContentHTML, closeBox, overflow) {

            var feature = new OpenLayers.Feature(markers, ll); 
            feature.closeBox = closeBox;
            feature.popupClass = popupClass;
            feature.data.popupContentHTML = popupContentHTML;
            feature.data.icon=icon;
            feature.data.overflow = (overflow) ? "auto" : "hidden";
                    
            var marker = feature.createMarker();

            var markerClick = function (evt) {
                if (this.popup == null) {
                    this.popup = this.createPopup(this.closeBox);
                    map.addPopup(this.popup);
                    this.popup.show();
                } else {
                    this.popup.toggle();
                }
                currentPopup = this.popup;
                OpenLayers.Event.stop(evt);
            };
            marker.events.register("mousedown", feature, markerClick);

            markers.addMarker(marker);
        }
 <?php } ?>

<?php
if (isset($text_marker) && $text_marker!='') {
?>
 var pois = new OpenLayers.Layer.Text( "Marker File",
                    { location:"<?php echo($text_marker);?>",
                      projection: map.displayProjection
                    });
    map.addLayer(pois);
 <?php } ?>
    </script>
<?php
}

function smarty_cms_help_function_osm()
{
?>
<p style="font-size:24px">OpenStreetMap tag for CMS Made Simple</p>

<p style="font-weight:bold;font-size:18px">Description</p>

<p>This tag tries to make available the most important features of a slippy map based on OpenStreetMap.</p>

<p style="font-weight:bold;font-size:18px">Parameters reference</p>
<table>
<tr><th>Parameter</th><th>Requires</th><th>Optional</th><th>Description</th></tr>
<tr><td>w</td><td>h, z</td><td>yes</td><td>Width, defaults at 500px</td></tr>
<tr><td>h</td><td>w, z</td><td>yes</td><td>Height, defaults at 500px</td></tr>
<tr><td>z</td><td>w, h</td><td>yes</td><td>Zoom, defaults at 13</td></tr>
<tr><td>clon</td><td>clat</td><td>no</td><td>Map centers at clon longitude</td></tr>
<tr><td>clat</td><td>clon</td><td>no</td><td>Map centers at clat latitude</td></tr>
<tr><td>mlon</td><td>mlat</td><td>yes</td><td>Longitude for a single marker</td></tr>
<tr><td>mlat</td><td>mlon</td><td>yes</td><td>Latitude for a single marker</td></tr>
<tr><td>mimg</td><td>mlon,mlat</td><td>yes</td><td>Marker Image a single marker</td></tr>
<tr><td>mtxt</td><td>mlon,mlat</td><td>yes</td><td>Text for popup of a single marker</td></tr>
<tr><td>gpx</td><td>-</td><td>yes</td><td>Url for a gpx file</td></tr>
<tr><td>markerfile</td><td>-</td><td>yes</td><td>Url for text file containing markers with a specific syntax</td></tr>
</table>
<br/>
<p style="font-weight:bold;font-size:18px">Notes</p>
-Pay attention to the layers: if you include for instance a markerfile and  single marker, the single marker won't be clickable (due to the layer stack)
-If you give mlon and mlat, map is centered on them.
<?php
}

function smarty_cms_about_function_osm()
{
?>
<p style="font-size:24px">OpenStreetMap tag for CMS Made Simple</p>
<p>Author Stefano Sabatini</p>
<p>Version 0.1.1</p>
<p>Last revision: 22/08/2013</p>
<br/>
<p style="font-weight:bold; font-size:18px">Changelog</p>
-0.1.1 : Fix release after testing
-0.1.0 : Fixing some things
-0.0.2 : Gpx and text layer
-0.0.1 : Initial release (single marker)
<?php
}
?>
