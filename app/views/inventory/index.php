
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
    <th>Hostname    </th>
    <th>Console User</th>
    <th>IP          </th>
    <th>OS          </th>
    <th>Last Inventory</th>
  </tr>
</thead>
<tbody>

<?foreach($inventory_items as $inventory):?>
  <?
    $machine = new Machine($inventory->serial);
    $reportdata = new Reportdata($inventory->serial);
?>
  <tr>
    <td>
        <a href="<?=url("inventory/detail/$inventory->serial")?>"><?=($machine->computer_name != '' ? $machine->computer_name : $inventory->serial)?></a>
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
