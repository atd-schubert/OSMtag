OSMtag
======

Tag for CMS Made Simple

1) Download from github and save the function.osm.php file into cms plugins directory (use the cmsms file file manager)
2) Create a page and simple put this row (please disable the WYSIWYG in cmsms editor for the page):
```
<<<<<<< HEAD
{osm w=300 h=300 clon="8.6831" clat="44.4042" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap"}
=======
{osm w=300 h=300 clat="44.4042" clon="8.6831" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap"}
>>>>>>> b2fd3471cad86d4614b2138a90cdc444c99d8a10
```

You can also set on every single marker a different icon img file by setting the mimg parameter
```
<<<<<<< HEAD
{osm w=300 h=300 clon="8.6831" clat="44.4042" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap" mimg="http://www.openstreetmap.org/assets/images/marker-icon.png"}
=======
{osm w=300 h=300 clat="44.4042" clon="8.6831" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap" mimg="http://www.openstreetmap.org/assets/images/marker-icon.png"}
>>>>>>> b2fd3471cad86d4614b2138a90cdc444c99d8a10
```

3) gpx sample (with a local gpx file...)
```
{osm w=300 h=300 clat="44.4042" clon="8.6831" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap" gpx="/uploads/test/mygpx.gpx"}
```

4) custom markers sample, some parameters can be optional (icon, iconsize and iconOffset):
```
{osm w=500 h=500 clat="44.404" clon="8.683" z="12" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap" gpx="/uploads/test/mygpx.gpx" markerfile="/uploads/test/mymarkers.txt"}
```


Here below, the markers.txt tab delimited file sample contents. Copy and paste the text from here can result in a not good file.

NOTE: the marker file must be tab delimited with the right eof.

```
lat     lon     title   description     icon    iconSize        iconOffset
44.4042 8.6831  Point 1 <b>Some text 1</b><pre>My text description extended</pre>       http://cdn.leafletjs.com/leaflet-0.4.4/images/marker-icon.png
44.4000 8.6800  Point 2 <b>Some text 2</b><pre>My text description extended <br/>Open this area on <a href=http://www.openstreetmap.org/#map=15/44.4042/8.6831 target=_blank>Open Openstreetmap</a></pre>       http://cdn.leafletjs.com/leaflet-0.4.4/images/marker-icon.png
```
