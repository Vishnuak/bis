<!doctype html>
<html class="rjp">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Journey Planner</title>

    <link rel="stylesheet" href="styles/select2/a515a284.select2.css"/>

    <link rel="stylesheet" href="styles/86e0aebc.components.css"/>

    <link rel="stylesheet" href="styles/8a6f813b.bootstrap.css"/>

    <link rel="stylesheet" href="styles/62730f3d.main.css"/>

    <!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/es5-shim/2.0.8/es5-shim.min.js"></script>
    <![endif]-->

  </head>
  <body>

    <script type="text/javascript">
      // Globals
      window.BuildProperties = {
        environment: "prod",
        corsproxy: "http://www.metroinfo.co.nz/journeyplanner/proxy.ashx?",
        silverrail_api_key: "",
        enable_analytics: true,
        enable_analytics_timing: true,
        walking_speed: 4,
        simple_earlier_later: false,
        breakout_location: "/journeyplanner/index.html"
      };
      // For testing only
      window.TestingHooks = {};
    </script>

    <!-- remember that external Javascripts can't be compiled by bower! -->
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyBhOe21KSbSl7acT7Xv83Fru-rLowfvfW4"></script>

    <script src="scripts/f62e5793.raygun.js"></script>

    <script src="scripts/d71ebdbd.init.js"></script>

    <script src="scripts/ac8c818b.components.js"></script>

    <script src="scripts/617b7984.plugins.js"></script>

    <script src="scripts/ac759daa.reset.js"></script>

    <script src="scripts/b53abae0.ember.js"></script>

    <!-- fix 'Couldn't autodetect L.Icon.Default.imagePath, set it manually.' error when building -->
    <script type="text/javascript">
      L.Icon.Default.imagePath = "images/leaflet";
    </script>

    <script src="scripts/cf0aaff6.templates.js"></script>

    <script src="scripts/85500ae6.main.js"></script>

  

    <!-- the application will be created within here -->
    <div id="emberjs-application"></div>

    <div class="emberjs-application-loading">
      <div class="spinner">
        <span class="glyphicon glyphicon-refresh"></span>
      </div>
      Loading...
  

  </body>
</html>