<?php
$this->registerCss("
    .page-break {
        page-break-after: always;
    }
");
?>
<?php foreach ($receipts as $receipt): ?>
    <div class="page-break">
        <?= $this->render('print-bill', ['id' => $receipt->id, 'receipt' => $receipt]) ?>
    </div>
<?php endforeach; ?>
<script>
    window.onload = function () {
        window.print();
    }
</script>
