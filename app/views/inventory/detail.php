<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.inventory').dataTable({
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[1,'asc']]
        });
    } );
</script>

<?$report_type = (object) array('name'=>'InventoryItems', 'desc' => 'inventory')?>
<?php View::do_dump('partials/machine_info.php', array(
  'report_type' => $report_type,
  'machine' => $machine,
  'report' => $report,
  'warranty' => $warranty
));?>


<? if (count($inventory_items)): ?>
    <legend>Inventory Items <span class='badge badge-info'><?=count($inventory_items)?></span></legend>
    <table class='table table-striped inventory table-condensed table-bordered'>
      <thead>
        <tr>
          <th>Name</th>
          <th>Version</th>
          <th>BundleID</th>
          <th>Path</th>
        </tr>
      </thead>
      <tbody>
      <? foreach($inventory_items as $item): ?>
      <?php $name_url=url('/inventory/bundle/'. rawurlencode($item->name)); ?>
      <?php $vers_url=$name_url . '/' . rawurlencode($item->version); ?>
        <tr>
          <td><a href='<?=$name_url?>'><?=$item->name?></a></td>
          <td><a href='<?=$vers_url?>'><?=$item->version?></a></td>
          <td><?=$item->bundleid?></td>
          <td><?=$item->path?></td>
        </tr>
      <? endforeach ?>
      </tbody>
    </table>
<? else: ?>
    <h2>Inventory Items</h2>
    <p><i>No inventory items.</i></p>
<? endif ?>