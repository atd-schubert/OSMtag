<?php
/*
 * OSM Plugin for CMS Made Simple
 * Author: Stefano Sabatini
 * Last rev: 23/08/2013
*/

function lsplit($str)
{
	return explode('/',$str);
}

function smarty_cms_function_leaflet($params, &$smarty)
{
    $div_size   = isset($params['size']) ? $params['size'] : '500/500'; // w/h
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
	
	$geojson = isset($params['geojson']) ? $params['geojson'] : '';
	$gpx= isset($params['gpx']) ? $params['gpx'] : '';
	//icon
?>

<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
<!--[if lte IE 8]>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css" />
<![endif]-->

<script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>
<script src="http://mapbox.github.io/togeojson/togeojson.js"></script>

<script type="text/javascript">
//https://code.google.com/p/microajax/
function microAjax(B,A){this.bindFunction=function(E,D){return function(){return E.apply(D,[D])}};this.stateChange=function(D){if(this.request.readyState==4){this.callbackFunction(this.request.responseText)}};this.getRequest=function(){if(window.ActiveXObject){return new ActiveXObject("Microsoft.XMLHTTP")}else{if(window.XMLHttpRequest){return new XMLHttpRequest()}}return false};this.postBody=(arguments[2]||"");this.callbackFunction=A;this.url=B;this.request=this.getRequest();if(this.request){var C=this.request;C.onreadystatechange=this.bindFunction(this.stateChange,this);if(this.postBody!==""){C.open("POST",B,true);C.setRequestHeader("X-Requested-With","XMLHttpRequest"); C.setRequestHeader("Content-type","application/x-www-form-urlencoded");C.setRequestHeader("Connection","close")}else{C.open("GET",B,true)}C.send(this.postBody)}};
</script>

<div id="mapdiv" style="width:<?php echo $size[0];?>px;height:<?php echo $size[1];?>px;"></div>  

<script type="text/javascript">
	var zoom=<?php echo $pos[0]?>;
	var lat=<?php echo $pos[1]?>;
	var lon=<?php echo $pos[2]?>;
	
	var iconurl='';
	<?php if ($marker!==NULL):?>
var mlat=<?php echo $marker[0]?>;
	var mlon=<?php echo $marker[1]?>;
	var mtxt="<?php echo $marker[2]?>";
	<?php endif;?>
	
	var osm = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
	var cyclemap = new L.TileLayer('http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png', {maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
	var mapquest = new L.TileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {subdomains: '1234', maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});	

	var map = new L.Map('mapdiv', {
    center: new L.LatLng(lat, lon),
    zoom: zoom,
    layers: [osm]
	});
	
	var baseMaps = {
    "Mapnik": osm,
	"OpenCycleMap": cyclemap,
    "Mapquest Open": mapquest	
	};
	L.control.layers(baseMaps).addTo(map);

function loadLayer(url)
{
var myLayer = L.geoJson(url,{
	onEachFeature:function onEachFeature(feature, layer) {
		if (feature.properties && feature.properties.name) {
			layer.bindPopup(feature.properties.name);
		}
	},
    pointToLayer: function (feature, latlng) {
        var marker=L.marker(latlng);
        return marker;
    }
}).addTo(map);
}

	<?php if ($marker!==NULL):?>	
	var marker = L.marker([mlat,mlon]).addTo(map);
	if (mtxt!='') marker.bindPopup(mtxt);
	map.addLayer(marker);
	<?php endif;?>
	
<?php if ($geojson!=''):?>

microAjax("<?php echo $geojson;?>",function (res) { loadLayer(JSON.parse(res)); } );

<?php endif;?>

<?php if ($gpx!=''):?>

microAjax("<?php echo $gpx;?>",function (res) { loadLayer(toGeoJSON.gpx(res)); } );

<?php endif;?>
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
<tr><td>size</td><td>w/h</td><td>false</td><td>Width and height in pixels</td></tr>
<tr><td>c</td><td>z/lat/lon</td><td>false</td><td>Zoom / latitude / longitude</td></tr>
<tr><td>m</td><td>lat/lon/text</td><td>true</td><td>To add a single marker (mind the text is optional; if contains slashes escape them with a backslash)</td></tr>
<tr><td>geojson</td><td>url</td><td>true</td><td>Makes use of Ajax to load a geojson file</td></tr>
</table>
<br/>
<p style="font-weight:bold;font-size:18px">Notes</p>

<?php
}
function smarty_cms_about_function_leaflet()
{
?>
<p style="font-size:24px">OpenStreetMap tag (Leaflet based) for CMS Made Simple</p>
<p>Author Stefano Sabatini</p>
<p>Version 0.0.2</p>
<p>Last revision: 23/08/2013</p>
<br/>
<p style="font-weight:bold; font-size:18px">Changelog</p>
-0.0.2 : Initial release (single marker and geojson)
<?php
}
?>
