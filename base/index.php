<?php
	date_default_timezone_set('America/New_York');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	session_start();
	include_once('dbtools.inc.php');
	$obj = new DbTools;
	if (!@$_SESSION['token']) {
		$obj->createToken();
	} else {
		if ($obj->checkToken($_SESSION['token'])) {
			//is okay
		} else {
			//echo 'no token';
		}
	}
?>

<html>

<head>
    <title>Ambiarc</title>
    <meta charset="UTF-8">

    <!--bootstrap resources-->
    <script src="../TemplateData_bak/js/jquery-2.2.4.min.js"></script>
    <script src="../TemplateData_bak/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!--colorpicker resources-->
    <link rel="stylesheet" media="all" href="../TemplateData_bak/css/bootstrap-colorpicker.css">
    <script src="../TemplateData_bak/js/bootstrap-colorpicker.js"></script>

    <link rel="stylesheet" media="all" href="../TemplateData_bak/css/bootstrap.min.css"/>
    <link rel="stylesheet" media="all" href="../css/demo-ui.css?nc=<?php echo time() ?>"/>
    <link rel="stylesheet" media="all" href="../css/panel.css?nc=<?php echo time() ?>"/>
    <link rel="stylesheet" media="all" href="../css/icons.css?nc=<?php echo time() ?>"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
    	document.token = "<?php echo $_SESSION['token'] ?>";
    </script>

    <script src="../js/BootstrapMenu.min.js"></script>
    <script src="../js/panel-ui.js?nc=<?php echo time() ?>"></script>
    <script src="../js/move-controls-ui.js?nc=<?php echo time() ?>""></script>

    <style>

    	#show_geoloc {
    		position: absolute;
    		top: 0px;
    		right: 0px;
    	}

    	.search-panel {
       		/*position: absolute;
    		top: 10px;
    		left: 10px;*/
    	}

    </style>

</head>

<body style="pointer-events: none">

<div id="show_geoloc">geo location</div>

<div id="main_container" class="container-fluid" style="z-index:100;">

    <div class="panel-section invisible">

        <div class="separate-block row buttons_row search-panel">
		  <select class="menu-buildings">
			<option class="bldg-opts" value="">:: Select Building ::</option>
			<option class="bldg-opts" value="Exterior">-- Reset Map --</option>
			<?php echo $obj->createBuildingAndFloorMenu(); ?>
			<input type="text" name="match" class="match"> <button class="search btn btn-info">search</button>
		  </select>
		</div>

        <!-- main panel -->
        <div id="main-panel" class="separate-block row poi-list-panel" style="pointer-events: all">

            <!-- poi list view -->
            <div id="points-section-button" class="header-button col-sm btn btn-default btn-primary btn-selected">
                Points of Interest
            </div>

            <div id="colors-section-button" class="header-button col-sm btn btn-default">
                Map Colors
            </div>
        </div>

        <div class="row panel-body-row poi-list-body">
            <div class="panel-body panel panel-default poi-container">
                <div class="init-poi-text col-sm">
                    <p>Right click the map to add a point of interest.</p>
                </div>
                <div class="sorting-section">
                    <div class="row" style="text-align: center; margin: 0;">
                        <div class="col-sm-12">
                            <label class="sort-label">Sort by:</label>
                        </div>
                    </div>

                    <div class="row" style="text-align: center; margin: 0;">

                        <!-- poi list view -->
                        <div style="width: 90%;margin: auto;">
                            <div class="filter-by-name sort-button col-sm-4 btn btn-default">
                                Name
                            </div>

                            <div class="filter-by-location sort-button col-sm-4 btn btn-default">
                                Location
                            </div>

                            <div class="filter-by-time sort-button col-sm-4 btn btn-default btn-primary btn-selected">
                                Time Added
                            </div>
                        </div>

                    </div>
                </div>

                <ul id="listPoiContainer" class="list-group">
                </ul>
            </div>

        </div>

        <!--colors panel-->
        <div class="row panel-body-row colors-panel invisible" style="pointer-events: all">
            <div class="panel-body panel panel-default poi-container">

                <div class="themes-section">
                    <div class="row" style="text-align: center; margin: 0;">
                        <div class="col-sm-12">
                            <label class="sort-label">Theme:</label>
                        </div>
                    </div>

                    <div class="row" style="text-align: center; margin: 0;">

                        <!-- colors view -->
                        <div style="width: 90%;margin: auto;">
                            <div class="light-theme-btn theme-btn col-sm-4 btn btn-default btn-primary btn-selected">
                                Light
                            </div>

                            <div class="dark-theme-btn theme-btn col-sm-4 btn btn-default">
                                Dark
                            </div>

                            <div class="custom-theme-btn theme-btn col-sm-4 btn btn-default">
                                Custom
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>

        <!-- poi details panel -->
        <div id="details-panel" class="separate-block row poi-details-panel invisible" style="pointer-events: all">

            <!-- poi list view -->
            <div id="details-header" class="row">
                <div class="header-button col-sm-5 btn btn-default btn-success back-to-list">
                    <label style="height: 100%;margin: 0;vertical-align: middle;">
                        <span class="fa fa-angle-left mr-2" style="font-size: 1.5rem;/* vertical-align: middle; */line-height: 2.4rem;display: inline-block;height: 100%;float: left;"></span>
                        <span style="line-height: 2.4rem;display: inline-block;height: 100%;vertical-align: middle;float: left;">Back to list</span>
                    </label>
                </div>

                <div id="undo-actions" class="header-button col-sm-4 btn btn-basic ml-2">
                    <label style="height: 100%;margin: 0;">
                        <span class="fa fa-undo mr-2" style="font-size: 1.1rem;/* vertical-align: middle; */line-height: 2.4rem;float: left;/* font-weight: 100; */"></span>
                        <span style="float: left;/* vertical-align: middle; */line-height: 2.4rem;">Undo</span>
                    </label>
                </div>

                <!--<button class="pull-geo">geo</button>-->
                <button class="header-button pull-geo btn btn-primary" style="height:30px;margin-left:10px;">geo</button>

                <div class="btn col-sm header-button pull-right saved-btn invisible">
                    <span>Saved</span>
                </div>
            </div>
        </div>

        <div class="row panel-body-row poi-details-panel invisible">
            <div class="panel-body panel panel-default poi-container">

                <!--<div class="row">
                    <div class="col-sm form-group">
                        <label class="poi-details-labelId">POI ID: </label>
                        <label id="poi-id" class="poi-details-id"></label>
                    </div>
                </div>-->

                <div class="row">
                    <div class="col-sm form-group">
                        <label class="poi-details-label">POI Name</label>
                        <input type="text" id="poi-title" class="poi-details-name poi-details-input form-control"/>
                    </div>
                </div>

                <div class="panel-group">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="poi-details-label">POI Type</label>
                            <select id="poi-type" class="poi-details-select form-control">
                                <option value="Text">Text only</option>
                                <option value="Icon">Icon only</option>
                                <option value="IconWithText">Text + Icon</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="poi-details-label">Font Size</label>
                            <input id="poi-font-size" type="text" class="poi-details-input form-control"/>
                        </div>
                    </div>

                    <!--<div id="select-icon-group" class="panel-group">
                        <div class="row">
                            <div id="poi-select-icon" class="form-inline input-group col-sm-8">
                                <div class="col-sm-3">
                                    <div id="poi-icon-image" class="poi-icon-image"></div>
                                </div>
                                <div class="col-sm-9 icon-btn-wrapper">
                                    <div class="btn btn-primary" id="poi-select-button">
                                        Select New Icon
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="centered-label">- or -</label>
                        </div>

                        <div class="row">
                            <div id="poi-browse-icons" class="form-inline justify-content-center col-sm-10">
                                <button class="browse-button input-group-addon col-sm-4">Browse</button>
                                <div id="poi-browse-text" class="form-control col-sm-6 mr-1" name="msg"
                                     placeholder="Additional Info" style="height: 25px;"></div>
                                <div class="poi-icon help-icon poi-question" data-toggle="tooltip"
                                     title="Icons should be designed and uploaded at 128px by 128px as transparent or non-transparent PNG format only."></div>
                            </div>
                            <input type="file" id="icon-file-hidden" class="custom-file-input">
                        </div>

                    </div>-->
                </div>

                <div class="panel-group">

                    <!--<div class="row">
                        <div class="form-group col-sm-6">
                            <label class="poi-details-label">Building ID</label>
                            <select id="poi-bulding-id" type="text" class="poi-details-input form-control"></select>
                        </div>
                        <div id="poi-floor-lists" class="form-group col-sm-6">
                            <label class="poi-details-label">Floor ID</label>
                        </div>
                    </div>-->

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="poi-details-label">Latitude</label>
                            <input id="poi-label-latitude" type="text" class="poi-details-input form-control"/>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="poi-details-label">Longitude</label>
                            <input id="poi-label-longitude" type="text" class="poi-details-input form-control"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="notification-text col-sm-12">
                            <p>*Right click anywhere on the map to reposition</p>
                        </div>
                    </div>
                </div>

                <div class="panel-group">
                    <div class="row">
                        <div class="form-group form-inline col-sm">
                            <input id="poi-tooltips-toggle" class="mr-1 mt-0" type="checkbox">
                            <span class="mr-1">Tooltip Callout</span>
                            <div id="tooltip_hint" class="poi-icon help-icon poi-question" data-toggle="tooltip"
                                 title="Callouts are areas of information that may expand out of an icon or label."></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm form-group">
                            <label class="poi-details-label">Tooltip title</label>
                            <input id="poi-tooltip-title" type="text" class="poi-details-input form-control"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm form-group">
                            <label class="poi-details-label">Tooltip body</label>
                            <textarea id="poi-tooltip-body" class="tooltip-body form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="panel-group">
                    <div class="row"></div>
                    <div class="row">
                        <ul id="poi-key-value-list"></ul>
                    </div>

                    <!--<div class="row">
                        <div class="col-sm-4">
                            <div id="poi-add-pair" class="btn btn-default btn-primary">
                                Add New Pair
                            </div>
                        </div>
                    </div>-->
                </div>

                <div class="row last-row">
                    <div class="col-sm form-group form-inline">
                        <input id="poi-creation-show" type="checkbox" class="mr-1 mt-0">
                        <span class="mr-1">Show On Creation</span>
                        <div data-toggle="tooltip"
                             title="If selected this map label will instantly appear after the map loads. Otherwise, this map label will remain in a hidden state until activated!"
                             class="poi-icon help-icon poi-question"></div>
                    </div>
                </div>

                <!--<div class="row" id="delete-point-section">
                    <div id="poi-delete" class="col-sm-4 btn btn-danger center-block">
                        Delete POI
                    </div>
                </div>-->

            </div>
        </div>

        <!-- icons panel -->

    </div>
    <div id="bootstrap" hidden></div>
    <div class="version-num" hidden>
        V1.2
    </div>
</div>

<!-- points of interest list element template -->
<div id="listPoiTemplate">
    <li class="poi-list-item row">
        <div class="col-sm1 list-icon-wrapper">
            <span class="glyphicon list-poi-icon"></span>
        </div>
        <div class="col-sm">
            <label class="list-poi-recordid"></label>
            <label class="list-poi-extra"></label>
            <label class="list-poi-roomnoold"></label>
            <label class="list-poi-roomnonew"></label>
            <label class="list-poi-label"></label>
            <span class="list-poi-bldg"></span>
            <span class="list-poi-floor"></span>
            <span class="list-poi-id"></span>
            <p class="list-poi-dtime"></p>
        </div>
    </li>
</div>

<div id="pairKeyValueTemplate">
    <li class="row pair-key-row">
        <div class="col-sm-4 form-group">
            <label class="poi-details-label">Key</label>
            <input type="text" class="poi-details-input form-control poi-new-key">
        </div>
        <div class="col-sm-4 form-group no-right-padding">
            <label class="poi-details-label">Value <span class="pair-type">(string)</span></label>
            <input type="text" data-type="string" class="poi-details-input form-control poi-new-value">
        </div>
        <div class="col-sm-4 list-icon-wrapper form-icon align-bottom row">
            <div class="key-value-icon fa fa-hashtag value-to-number"></div>
            <div class="key-value-icon fa fa-quote-right value-to-string selected-value-type"></div>
            <div class="key-value-icon fa fa-trash delete-pair"></div>
        </div>
    </li>
</div>

<!--<iframe src="map.html" id="ambiarcIframe">-->
    <!--Your browser doesn't support iframes-->
<!--</iframe>-->

<div>
    <div id="controls-section" style="pointer-events: all">
        <ul>
            <li class="">
                <div class="controls-btn ctrl-zoom-in" aria-hidden="true"></div>
            </li>
            <li class="">
                <div class=" controls-btn ctrl-rotate-left" aria-hidden="true"></div>
                <div class="controls-btn ctrl-rotate-right" aria-hidden="true"></div>
            </li>
            <li class="">
                <div class=" controls-btn ctrl-zoom-out" aria-hidden="true"></div>
            </li>
        </ul>
    </div>
</div>



</body>

<script>

    var mapUrl =  'SDK/map.html';
    var ambiarcIframe = document.createElement('iframe');
        ambiarcIframe.id = 'ambiarcIframe';
        ambiarcIframe.src = mapUrl;

        //adding ctrl + click to trigger right click
        ambiarcIframe.onload = function(){
            document.getElementById("ambiarcIframe").contentWindow.addEventListener('keydown', keyDownHandler);
            document.getElementById("ambiarcIframe").contentWindow.addEventListener('keyup', keyUpHandler);
            document.getElementById("ambiarcIframe").contentWindow.addEventListener('click', bootstrapMenuTrigger);
        };

    $('#pairKeyValueTemplate').after(ambiarcIframe);

	//     var getMapName = function(name){
	//         var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	//         if (results==null) {
	//             return null;
	//         } else {
	//             return decodeURI(results[1]) || 0;
	//         }
	//     };
	//
	//
	//     var mapUrl =  'SDK/map.html';
	//     var ambiarcIframe = document.createElement('iframe');
	//         ambiarcIframe.id = 'ambiarcIframe';
	//         ambiarcIframe.src = mapUrl;
	//
	//         //adding ctrl + click to trigger right click
	//         ambiarcIframe.onload = function(){
	//             document.getElementById("ambiarcIframe").contentWindow.addEventListener('keydown', keyDownHandler);
	//             document.getElementById("ambiarcIframe").contentWindow.addEventListener('keyup', keyUpHandler);
	//             document.getElementById("ambiarcIframe").contentWindow.addEventListener('click', bootstrapMenuTrigger);
	//         };
	//
	//     var mapFolder = getMapName('map');
	//     var mapUrl =  (mapFolder !== null) ? mapFolder+'/map.html' : 'defaultMap.html';
	//     var ambiarcIframe = document.createElement('iframe');
	//         ambiarcIframe.id = 'ambiarcIframe';
	//         ambiarcIframe.src = mapUrl;
	//
	//     $('#pairKeyValueTemplate').after(ambiarcIframe);

	$.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results==null) {
		   return null;
		} else {
		   return decodeURI(results[1]) || 0;
		}
	}

    $(document).on("change", "select.menu-buildingsZZZ", function(e){

    	showPoiList();

		var ambiarc = $("#ambiarcIframe")[0].contentWindow.Ambiarc;

		ambiarc.exitBuilding();

		var locArr = this.value.split('@');
		ambiarc.focusOnFloor(locArr[0], locArr[1], 300);

		setTimeout(function(){
			destroyAllLabels();
			//window.building = this.value;
			window.floor = locArr[1];
			pullDataFromApi();
		},1);
	});

	$(document).ready(function() {

		// 	$('.bldg-opts').attr('selected',false);
		// 	var building = $.urlParam('building');
		// 	if (building > '1') {
		// 		$('.menu-buildings option[value='+building+']').attr('selected',true);
		// 	}

		$('.bldg-opts').attr('selected',false);
		var floor = $.urlParam('floor');
		if (floor > '1') {
			$('.menu-buildings option[value='+floor+']').attr('selected',true);
		}

		setInterval(getLocation, 1000);

	});

	$(document).on('click', '.pull-geo', function(e){

		var ambiarc = $("#ambiarcIframe")[0].contentWindow.Ambiarc;
		ambiarc.EnterOverheadCamera();

		var geo = document.getElementById("show_geoloc").innerHTML;
		geo = geo.replace('<br>',':');
		var split = geo.split(':');
		console.log(split);

		var lat = split[1].trim();
		var lon = split[3].trim();

		//$('#poi-label-latitude').focus();
		$('#poi-label-latitude').val(lat);
		updatePoiDetails('latitude', lat);
		//$('#poi-label-latitude').blur();

		//$('#poi-label-longitude').focus();
		$('#poi-label-longitude').val(lon);
		updatePoiDetails('longitude', lon);
		//$('#poi-label-longitude').blur();

	});

	var x = document.getElementById("show_geoloc");
	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else {
			x.innerHTML = "Geolocation is not supported by this browser.";
		}
	}
	function showPosition(position) {
		x.innerHTML = "Latitude: " + position.coords.latitude +
		"<br>Longitude: " + position.coords.longitude;
	}

	//console.log('geo');

    $('#pairKeyValueTemplate').after(ambiarcIframe);

</script>
</html>
