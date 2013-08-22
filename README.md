OSMtag
======

Tag for CMS Made Simple

1) Download from github and save the function.osm.php file into cms plugins directory (use the cmsms file file manager)
2) Create a page and simple put this row (please disable the WYSIWYG for the page):
```
    {osm w=300 h=300 clon="8.6831" clat="44.4042" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap"}
```

3) gpx sample (with a local gpx file...)
```
    {osm w=300 h=300 clat="44.4042" clon="8.6831" z="18" mlon="8.6831" mlat="44.4042" mtxt="Very good you are on OpenStreetMap" gpx="/uploads/test/mygpx.gpx"}
```

4) custom markers sample, some parameters can be optional (icon, iconsize and iconOffset):
```
lat     lon     title   description     icon    iconSize        iconOffset
44.4042 8.6831  Point 1 <b>Some text 1</b><pre>My text description extended</pre>       http://cdn.leafletjs.com/leaflet-0.4.4/images/marker-icon.png
44.4000 8.6800  Point 2 <b>Some text 2</b><pre>My text description extended <br/>Open this area on <a href=http://www.openstreetmap.org/#map=15/44.4042/8.6831 target=_blank>Open Openstreetmap</a></pre>       http://cdn.leafletjs.com/leaflet-0.4.4/images/marker-icon.png
```

Pay attention the marker file must be tab delimited with the right eof.
