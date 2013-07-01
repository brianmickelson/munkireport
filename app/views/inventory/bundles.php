<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.table').dataTable({
            "iDisplayLength": 25,
            "sPaginationType": "bootstrap",
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "bStateSave": true,
            "aaSorting": [[0,'asc']]
        });
    } );
</script>
<legend>Application Bundles <span class='badge badge-info'><?=formatted_count($inventory)?></span></legend>
<table class='table table-striped table-condensed table-bordered'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Version</th>
    </tr>
  </thead>
  <tbody>
    <?foreach($inventory as $name => $value):?>
    <?php $name_url=url('/inventory/bundle/'. rawurlencode($name)); ?>
    <tr>
      <td>
        <a href='<?=$name_url?>'><?=$name?></a>
      </td>
      <td>
        <?foreach($value as $version => $count):?>
        <?php $vers_url=$name_url . '/' . rawurlencode($version); ?>
        <a href='<?=$vers_url?>'><?=$version?>
          <span class='badge badge-info pull-right'><?=$count?></span>
        </a><br />
        <?endforeach?>
      </td>
    </tr>
    <?endforeach?>
  </tbody>
</table>