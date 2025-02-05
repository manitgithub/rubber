<div class="card bg-template shadow mt-4 h-190">
    <div class="card-body">
        <div class="row">
            <div class="col-auto">
                <a href="<?= $itemDetail->photoViewer ?>" data-toggle="lightbox" data-caption="<?= $itemDetail->part_name ?>">
                    <figure class="avatar avatar-60">
                        <img src="<?= $itemDetail->photoViewer ?>" alt="">
                    </figure>
                </a>
            </div>
            <div class="col pl-0 align-self-center">
                <h5 class="mb-1"><?= $itemDetail->part_name ?></h5>
                <p class="text-mute "><b><?= $itemDetail->part_code ?></b> : <?= $itemDetail->category->name ?> <small class="float-right text-mute">ข้อมูล ณ วันที่ <?= date('Y-m-d') ?></small></p>
            </div>
        </div>
    </div>
</div>
<div class="container top-100">
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5 class="mb-3"><small>รายละเอียดสินค้า</small></h5>
            <p class="text-secondary text-mute"><?= $itemDetail->description ?></p>
        </div>
        <div class="card-footer bg-none">
            <div class="row">
                <div class="col text-center">
                    <p><?= $itemDetail->uom->uom_name ?><br><small class="text-mute">หน่วยเล็กสุด</small></p>
                </div>
                <div class="col text-center border-left-dotted">
                    <p><?= number_format($itemDetail->unit_price, 2) ?><br><small class="text-mute">ราคา/หน่วยเล็กสุด</small></p>
                </div>
                <div class="col text-center border-left-dotted">
                    <p><?= $itemDetail->min_qty ?><br><small class="text-mute">สต๊อคขั้นต่ำ</small></p>
                </div>
            </div>
        </div>
    </div>
</div>