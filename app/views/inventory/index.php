
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.inventory').dataTable({
            "iDisplayLength": 25,
            "sPaginationType": "bootstrap",
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[4,'desc']]
        });
    } );
</script>

<legend>Inventory Clients <span class="badge badge-info"><?=count($inventory_items)?></span></legend>

<table class="table table-striped inventory table-condensed table-bordered">
<thead>
  <tr>
    <th>Client      </th>
    <th>Console User</th>
    <th>IP          </th>
    <th>OS Version  </th>
    <th>Last Inventory</th>
  </tr>
</thead>
<tbody>

<?foreach($inventory_items as $inventory):?>
  <?
    $machine = new Machine($inventory->serial);
    $reportdata = new Munkireport($inventory->serial);
?>
  <tr>
    <td><?php View::do_dump('partials/machine_button_group.php', array(
            'serial_number' => $machine->serial_number,
            'hostname' => $machine->computer_name,
            'ip' => $reportdata->remote_ip,
            'controller_name' => 'inventory',
            'action_name' => 'detail'
        ))?>
    </td>
    <td><?=$reportdata->console_user?></td>
    <td><?=$reportdata->remote_ip?></td>
    <td><?=$machine->os_version?></td>
    <td>
        <?=strftime('%x %r %Z', $inventory->timestamp)?>
    </td>
  </tr>
<?endforeach?>
</tbody>
</table>
