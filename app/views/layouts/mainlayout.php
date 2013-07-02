<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo $layout_page_title == '' ? Config::get('siteName') : $layout_page_title?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="text/javascript" src="<?php echo WEB_FOLDER;?>assets/js/jquery.js"></script>
  <script type="text/javascript" src="<?php echo WEB_FOLDER;?>assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo WEB_FOLDER;?>assets/js/DataTables/media/js/jquery.dataTables.js"></script>
  <script type="text/javascript" src="<?php echo WEB_FOLDER;?>assets/js/DataTables/media/js/jquery.dataTables.bootstrap.js"></script>
  <script type="text/javascript" src="<?php echo WEB_FOLDER;?>assets/js/mainlayout.js"></script>
<?php


$controller = KISS_Controller::get_instance();
$controller_name = $controller->controller;
$route = $controller_name . '/' . $controller->action;

$layout_styles = array_merge($layout_styles, array(
  "dataTables-bootstrap.css",
  "bootstrap.min.css",
  "bootstrap-responsive.min.css",
  "font-awesome",
  "style.css"));

// include any specified stylesheets
foreach ($layout_styles as $style)
{
  echo '  <link rel="stylesheet" type="text/css" media="screen" href="'
    . WEB_FOLDER . "assets/css/" . $style . '"/>' . "\n";
}
?>
</head>
<body>
  <div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="brand" href="<?=url('')?>"><?=Config::get('siteName')?></a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li <?=$controller_name == 'dashboard' ? "class='active'" : '';?>>
            <a href="<?php echo url('dashboard/index');?>">
              <i class="icon-th-large"></i> Dashboard
            </a>
          </li>
          <li <?=$controller_name == 'clients' ? "class='active'" : '';?>>
            <a href="<?php echo url('clients/index');?>">
              <i class="icon-group"></i> Clients
            </a>
          </li>
          <li <?=$controller_name == 'show' ? "class='active'" : '';?>>
            <a href="<?php echo url('show/reports');?>">
              <i class="icon-bar-chart"></i> Reports
            </a>
          </li>
          <li class='dropdown <?=$controller_name == 'inventory' ? "active" : '';?>'>
            <a class='dropdown-toggle' href="#" data-toggle='dropdown'>
              <i class="icon-info-sign"></i> Inventory
              <b class="caret"></b>
            </a>
            <ul class='dropdown-menu'>
              <li>
                <a href="<?php echo url('inventory/bundles');?>">
                  Application Bundles
                </a>
              </li>
              <li>
                <a href="<?php echo url('inventory/index');?>">
                  Machines
                </a>
              </li>  
            </ul>
          </li>
          <li <?=$controller_name == 'manifests' ? "class='active'" : '';?>>
            <a href="<?php echo url('manifests/index');?>">
              <i class="icon-edit"></i> Manifests
            </a>
          </li>
          <li <?=$controller_name == 'catalogs' ? "class='active'" : '';?>>
            <a href="<?php echo url('catalogs/index');?>">
              <i class="icon-list-alt"></i> Catalogs
            </a>
          </li>
        </ul>
        <form class="navbar-form pull-right">
          <a class="btn" href="<?=url('auth/logout')?>">Logout</a>
        </form>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

  <div class="container">
    <?php echo $layout_content;?>

    <div class="navbar navbar-static-bottom">
      <div class="navbar-inner">
        <ul class="nav">
          <li>
            <p class="navbar-text">
              <small>MunkiReport version <?=$GLOBALS['version']?></small>
            </p>
          </li>
          <?php
              $controller_name = $controller->controller;
              $action_name = $controller->action;
              $params = $controller->params;
              $param_string = '';
              for($i = 0; $i < count($params); $i++)
              {
                $param_string .= "/" . urlencode($params[$i]);
              }
              $view_dir = Config::get('paths.view') . $controller_name . "/";
              $formats = array();
              foreach(array_filter(glob($view_dir . "/*"), 'is_dir') as $dir)
              {
                if (file_exists($dir . "/" . $action_name . ".php"))
                {
                  $format = basename($dir);
                  $formats[] = "<a href='" . url(
                      $controller_name
                      . "/" . $action_name . $param_string
                      . "." . $format)
                    . "'>" . $format . "</a>";
                }
              }
              if (count($formats) > 0)
              {
                echo '<li class="divider-vertical"></li>
                      <li>
                        <p class="navbar-text">
                        <small>This page is available in: '
                      . implode(', ', $formats)
                      . '</small></p></li>';
              }
              ?>
          </li>
        </ul>
      </div>
    </div>

  </div>
<?php


// include any javascript files
foreach ($layout_scripts as $script)
{
  echo '  <script type="text/javascript" src="' . WEB_FOLDER . "assets/js/" . $script . '"></script>' . "\n";
}


?>
</body>
</html>