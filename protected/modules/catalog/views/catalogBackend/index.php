<?php
    $this->breadcrumbs = array(
        Yii::app()->getModule('catalog')->getCategory() => array(),
        Yii::t('CatalogModule.catalog', 'Products') => array('/catalog/catalogBackend/index'),
        Yii::t('CatalogModule.catalog', 'Manage'),
    );

    $this->pageTitle = Yii::t('CatalogModule.catalog', 'Manage products');

    $this->menu = array(
        array('icon' => 'list-alt', 'label' => Yii::t('CatalogModule.catalog', 'Manage products'), 'url' => array('/catalog/catalogBackend/index')),
        array('icon' => 'plus-sign', 'label' => Yii::t('CatalogModule.catalog', 'Add a product'), 'url' => array('/catalog/catalogBackend/create')),
    );
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('CatalogModule.catalog', 'Products'); ?>
        <small><?php echo Yii::t('CatalogModule.catalog', 'administration'); ?></small>
    </h1>
</div>

<button class="btn btn-small dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="icon-search">&nbsp;</i>
    <?php echo CHtml::link(Yii::t('CatalogModule.catalog', 'Find products'), '#', array('class' => 'search-button')); ?>
    <span class="caret">&nbsp;</span>
</button>

<div id="search-toggle" class="collapse out search-form">
<?php
Yii::app()->clientScript->registerScript('search', "
    $('.search-form form').submit(function() {
        $.fn.yiiGridView.update('good-grid', {
            data: $(this).serialize()
        });
        return false;
    });
");
$this->renderPartial('_search', array('model' => $model));
?>
</div>

<br/>

<p><?php echo Yii::t('CatalogModule.catalog', 'This section describes products manager'); ?></p>

<?php $this->widget('yupe\widgets\CustomGridView', array(
    'id'           => 'good-grid',
    'type'         => 'condensed',
    'dataProvider' => $model->search(),
    'filter'       => $model,
    'columns'      => array(
        'id',
        array(
            'name'  => 'name',
            'type'  => 'raw',
            'value' => 'CHtml::link($data->name, array("/catalog/catalogBackend/update", "id" => $data->id))',
        ),
        array(
            'name'  => 'alias',
            'type'  => 'raw',
            'value' => 'CHtml::link($data->alias, $data->permaLink)',
        ),
        array(
            'name'   => 'category_id',
            'type'   => 'raw',
            'value'  => '$data->categoryLink',
            'filter' => CHtml::listData(Yii::app()->getModule('catalog')->getCategoryList(),'id','name')
        ),
        'price',
        'article',
        array(
            'name'  => 'is_special',
            'type'  => 'raw',
            'value'  => '$this->grid->returnBootstrapStatusHtml($data, "is_special", "Special", array("minus", "star"))',
            'filter' => Yii::app()->getModule('catalog')->getChoice()
        ),
        array(
            'name'   => 'status',
            'type'   => 'raw',
            'value'  => '$this->grid->returnBootstrapStatusHtml($data, "status", "Status", array("time", "ok-sign", "minus-sign"))',
            'filter' => $model->getStatusList()
        ),
        array(
            'name'   => 'user_id',
            'type'   => 'raw',
            'value'  => 'CHtml::link($data->user->getFullName(), array("/user/catalogBackend/view", "id" => $data->user->id))',
            'filter' => CHtml::listData(User::model()->cache(Yii::app()->getModule('yupe')->coreCacheTime)->findAll(),'id','nick_name')
        ),       
        array(
            'name'  => 'create_time',
            'value' => 'Yii::app()->getDateFormatter()->formatDateTime($data->create_time, "short", "short")',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>