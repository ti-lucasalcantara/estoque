<?=$this->extend('restrito/template/principal')?>



<?= $this->section('conteudo') ?>
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Empty</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>
                Import</button>
            <a href="javascript:void(0);"  class="btn btn-primary btn-pill" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-calendar me-2 fs-14"></i> Search By Date</a>
            <div class="dropdown-menu border-0">
                    <a class="dropdown-item" href="javascript:void(0);">Today</a>
                    <a class="dropdown-item" href="javascript:void(0);">Yesterday</a>
                    <a class="dropdown-item active" href="javascript:void(0);">Last 7 days</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last 30 days</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last 6 months</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last year</a>
            </div>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                Something text type here.....
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
<?= $this->endSection() ?>


<?= $this->section('css') ?>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?= $this->endSection() ?>
