<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.table').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[1,'asc']]
        });
    } );
</script>
<?php
  $controller = KISS_Controller::get_instance();
  $route = $controller->controller . "/" . $controller->action 
    . "/" . $name;
  $selected_version = isset($version) && $version !='' ? $version : "All";
?>
<legend><?=$name?> 
  <div class="btn-group">
    <a class='btn btn-small dropdown-toggle' href="#" data-toggle="dropdown">
      <?php echo $selected_version;?>
      <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="<?php echo url($route);?>">All</a></li>
      <li class="divider"></li>
    <?php
    foreach($all_versions as $ver)
    {
      echo "<li "
      . ($ver['version'] == $selected_version ? 'class="disabled"' : '') . ">"
        . "<a href='" . url($route . "/" . $ver['version']) . "'>"
          . $ver['version']
          . "<span class='badge pull-right'>"
            . $ver['num']
          . "</span></a>"
        . "</li>";
    }
    ?></ul>
  </div>

  <span class='badge badge-info'><?php echo count($inventory_items);?></span>
</legend>
<? if (count($inventory_items)): ?>
<table class='table table-striped table-condensed table-bordered'>
    <thead>
        <tr>
          <th>Hostname</th>
          <th>Username</th>
          <th>Version</th>
          <th>BundleID</th>
          <th>CFBundleName</th>
          <th>Path</th>
        </tr>
    </thead>
    <tbody>
    <? foreach($inventory_items as $item): ?>
    <? $url=url('/inventory/detail/' . $item['serial']) ?>
        <tr>
            <td>
              <a href='<?= $url ?>'>
                <?= $item['hostname'] ?>
              </a>
            </td>
            <td><?= $item['username'] ?></td>
            <td><?= $item['version'] ?></td>
            <td><?= $item['bundleid'] ?></td>
            <td><?= $item['bundlename'] ?></td>
            <td><?= $item['path'] ?></td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>
<? else: ?>
<p><i>No results.</i></p>
<? endif ?>