<?php
/*
 * OSM Plugin for CMS Made Simple
 * Author: Stefano Sabatini
 * Author: Arne Schubert
 * Last rev: 10/12/2017
*/

function lsplit($str)
{
	return explode('/',$str);
}

function smarty_cms_function_leaflet($params, &$smarty)
{
    $div_size   = isset($params['size']) ? $params['size'] : '100%/100%'; // w/h
	$size=lsplit($div_size);
	if (count($size)==1) $size[1]=$size[0];
	
	$position= isset($params['c']) ? $params['c'] : '0/0/0'; // z/lat/lon
	$pos=lsplit($position);
	if (count($pos)==1) $pos[1]='0';
	if (count($pos)==2) $pos[2]='0';
	
	$marker= isset($params['m']) ? $params['m'] : NULL; // lat/lon/text (slash must be escaped)
	if($marker!=NULL)
	{
		$marker=preg_split("~\\\[\/]{1}(*SKIP)(*FAIL)|\/~s", $marker);
		if (count($marker)==1) $marker[1]='0'; //if not text don't popup
		if (count($marker)==2) $marker[2]='';
		
		$marker[2]=str_replace('"','\"',$marker[2]);
	}
?>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/1.2.0/leaflet.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>

<style>
#mapdiv{
  width:<?php echo $size[0];?>;
  height:<?php echo $size[1];?>;
}

</style>
<div id="mapdiv"></div>  

<script type="text/javascript">
	var zoom=<?php echo $pos[0]?>;
	var lat=<?php echo $pos[1]?>;
	var lon=<?php echo $pos[2]?>;

	var osm = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
	var otm = new L.TileLayer('http://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
	var seamap = new L.TileLayer('http://t{s}.openseamap.org/seamark/{z}/{x}/{y}.png', {subdomains: '123', maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});

	var map = new L.Map('mapdiv', {
      center: new L.LatLng(lat, lon),
      zoom: zoom,
      layers: [osm]
	});

    var baseMaps = {
        "OSM": osm,
        "OTM": otm
    };
    var overlayMaps = {
        "OpenSeaMap": seamap,
    };
	L.control.layers(baseMaps, overlayMaps).addTo(map);

</script>
<?php
}

function smarty_cms_help_function_leaflet()
{
?>

<p style="font-size:24px">OpenStreetMap tag for CMS Made Simple</p>

<p style="font-weight:bold;font-size:18px">Description</p>

<p>This tag is based on Leaflet library and displays markers and geojsons on top of an OpenStreetMap layer.</p>

<p style="font-weight:bold;font-size:18px">Parameters reference</p>
<style>table#leaf{width:100%}</style>
<table id="leaf">
<tr><th>Parameter</th><th>Format</th><th>Optional</th><th>Description</th></tr>
<tr><td>size</td><td>w/h</td><td>false</td><td>Width and height (add px or %, ex 300px/300px or 100%/100%, if both height and width are percentages, height stands at 100px)</td></tr>
<tr><td>c</td><td>z/lat/lon</td><td>false</td><td>Zoom / latitude / longitude</td></tr>
</table>
<br/>
<p style="font-weight:bold;font-size:18px">Notes</p>

<?php
}
function smarty_cms_about_function_leaflet()
{
?>
<p style="font-size:24px">OpenStreetMap tag (Leaflet based) for CMS Made Simple</p>
<p>Author Stefano Sabatini, Arne Schubert with the help of Falk Zscheile</p>
<p>Version 0.0.4</p>
<p>Last revision: 10/12/2017</p>
<br/>
<p style="font-weight:bold; font-size:18px">Changelog</p>
-0.0.4 : Remove not needed functions (for us) and use common cdns
-0.0.3 : Fullscreen control
-0.0.2 : Initial release (single marker and geojson)
<?php
}
?>
