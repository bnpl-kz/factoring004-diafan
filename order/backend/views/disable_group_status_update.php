<?php
    $statuses = implode(',', array_map([\BnplPartners\Factoring004Diafan\Helper\Config::class, 'get'], [
        'factoring004_status_delivery',
        'factoring004_status_return',
        'factoring004_status_cancel',
    ]));
?>
<div style="display: none">
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const elements = document.getElementsByName('group_status_id');
        const disabledValues = [<?=$statuses?>];

        for (const element of elements) {
          if (disabledValues.indexOf(element.value) !== -1) {
            element.disabled = true;
          }
        }
      })
    </script>
</div>