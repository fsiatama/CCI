<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Solución Tecnológica MADR</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="css/bootstrap.css">

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/resources/css/ext-all.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/css/Portal.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/resources/css/xtheme-gray-extend.css">
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/resources/css/forms.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/shared/icons/silk.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/css/ColumnHeaderGroup.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/statusbar/css/statusbar.css">
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/remoteTree/css/remotetree.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/css/Spinner.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/css/MultiSelect.css"/>
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/selectBox/superboxselect-gray-extend.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/passwordMeter/passwordmeter.css" />
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/checktree/css/Ext.ux.tree.CheckTreePanel.css">

		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/css/Ext.ux.grid.RowActions.css"/>
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/css/RowEditor.css"/>
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/menu/menus.css"/>
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/datepicker/datepickerplus.css"/>
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/LinkPanel/Ext.ux.LinkPanel.css"/>
		<link rel="stylesheet" type="text/css" href="js/vendor/ext-3.4.0/examples/ux/fileuploadfield/css/fileuploadfield.css"/>
		
		<link rel="stylesheet" href="css/main.css">
		<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

	</head>
    <body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
    
		<div id="loading-mask"></div>
		<div id="loading">
			<span id="loading-message">Loading. Please wait...</span>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		
		<script type="text/javascript" src="js/vendor/ext-3.4.0/adapter/ext/ext-base.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/ext-all.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/shared/extjs/App.js"></script>

		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/datepicker/ext.ux.datepickerplus-min.js"></script>

		<script type='text/javascript' src='js/vendor/ext-3.4.0/src/locale/ext-lang-es.js'></script>
		<script type='text/javascript' src='js/vendor/ext-3.4.0/examples/ux/datepicker/ext.ux.datepickerplus-lang-es.js'></script>

		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/remoteComponent/Ext.ux.Plugin.RemoteComponent.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/state/SessionProvider.js"></script>

		<script type="text/javascript" src="js/cookies.js"></script>
		<script type="text/javascript" src="js/using.js"></script>
		<script type="text/javascript" src="js/using-register.js"></script>
		<script type="text/javascript" src="js/dic/lang-es.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/grid/Ext.ux.grid.RowActions.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/RowExpander.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Portal.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/PortalColumn.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Portlet.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.FieldHelp.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.SearchField.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/statusbar/StatusBar.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/RowEditor.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/SlidingTabs.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.inactivityMonitor.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.util.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/PanelResizer.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/LinkPanel/Ext.ux.LinkPanel.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/remoteValidation/Ext.ux.plugins.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/passwordMeter/Ext.ux.PasswordMeter.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Spinner.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/SpinnerField.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.PanelCollapsedTitle.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/fileuploadfield/FileUploadField.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/MultiSelect.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/ItemSelector.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/BufferView.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.grid.Excel.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.grid.Search.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/checktree/js/Ext.ux.tree.CheckTreePanel.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/Ext.ux.tree.RemoteTreePanel.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/remoteTree/js/Ext.ux.tree.TreeFilterX.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/ColumnHeaderGroup.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/grid/Ext.ux.gridOrden.js"></script>
		<script type="text/javascript" src="js/vendor/ext-3.4.0/examples/ux/selectBox/SuperBoxSelect2.js"></script>		

		<script type="text/javascript" src="js/vendor/FusionCharts/Charts/FusionCharts.js"></script>
		<script type="text/javascript" src="js/header.js"></script>
		<script type="text/javascript" src="js/footer.js"></script>

		<script type="text/javascript" src="js/left.js"></script>
		<script type="text/javascript" src="js/centro.js"></script>
		<script type="text/javascript" src="js/main_app.js"></script>	
    </body>
</html>
