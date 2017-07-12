<? Yii::app()->less->register('mobileActions'); ?>
<? Yii::app()->less->registerAll(); ?>
<?
/* @var $productList  \application\lib\api\util\ProductListResult */
/* @var $action \application\lib\api\model\Promotion  */
/* @var $force_category boolean */
/* @var $categoryRoots   \application\lib\api\model\Category[] */
/* @var $currentCategory \application\lib\api\model\Category */

$breadcrumbs = array(
    array(
        "url" => Yii::app()->createUrl('action/index'),
        "title" => "Акции",
    ),
    array(
        "title" => CHtml::encode($this->pagetitle),
    ),
);

$actionStart = '17.03.2017';
$actionEnd = '21.03.2017 04:00:00';

$changeTime = '';
$endTime = time() <= $changeTime ? strtotime($actionStart) - time() : strtotime($actionEnd) - time();


//Скрываем баннер под заголовком
application\helpers\ViewHelper::hideBannerType(Banner::CATEGORY_ID_ALL_PAGE_BELOW_HEAD);

?>


<div class="subcategory action-page hundred-hours">
    <div class="hundred-hours__wrap">
        <div class="hundred-hours__titles">
            <img class="hundred" src="../res/base/img/actions/100.png" alt="100 товаров, часов, выгодно">
        </div>
        
        <div id="promo-timer" class="promo-timer<? (time() >= $changeTime) ? "promo-timer_s" : "" ?>">
            <div class="promo-timer__title">до конца акции осталось:</div>
            <div class="promo-timer__section">
                <div class="promo-timer__value js-timer-value" id="hours-count">0</div>
                <div class="promo-timer__word" id="hours-word">часы</div>
            </div>
            <div class="promo-timer__divider">:</div>
            <div class="promo-timer__section">
                <div class="promo-timer__value js-timer-value" id="minutes-count">0</div>
                <div class="promo-timer__word" id="minutes-word">минуты</div>
            </div>
            <div class="promo-timer__divider">:</div>
            <div class="promo-timer__section">
                <div class="promo-timer__value js-timer-value" id="seconds-count">0</div>
                <div class="promo-timer__word" id="seconds-word">секунды</div>
            </div>
        </div>
 
        <script type="text/javascript">
            function promoTimer(rTime){
                var remTime = (rTime - 1) * 1000,
                    date = new Date(),
                    day = parseInt((rTime /3600)/24),
                    timer = setInterval(function() {
                        date.setTime(remTime);
                        var hours = day ? date.getUTCHours() + day * 24 : date.getUTCHours();
                        document.getElementById('hours-count').innerHTML = hours;
                        document.getElementById('minutes-count').innerHTML = date.getMinutes();
                        document.getElementById('seconds-count').innerHTML = date.getSeconds();
                        if (remTime <= 0){
                            clearInterval(timer);
                            var values = document.querySelectorAll('.js-timer-value');
                            for (var i = 0; i < values.length; i ++) {
                                if (values[i]) values[i].innerHTML = '0';
                            }
                            location.reload();
                        }
                        remTime = remTime - 1000;
                    }, 1000);
            }
            promoTimer(<?= $endTime;?>);
        </script>
        <p class="image-center">
            <img class = "hurry" src="../res/base/img/actions/hurry.png" alt="Успейте купить">
        </p>
        <div class="hundred-hours__show">
             <div class="content-area">
                <a href="<?= $action->getUrlPage(); ?>/catalog" class="hundred-hours__show-all">Смотреть все товары</a>
            </div>
        </div>
    </div>
</div>

<? 
    $productListResultFilter = new application\lib\api\util\ProductListResultFilter($productList); 
    $productListResultFilter->appendFilter(function($self) use ($action) {  
        $self->setItems(array_slice($self->getItems(), 0, 8));
    });
    $productListResultFilter->applyFilters();
?>

<? $itemsWidget = $this->widget('application.components.widgets.GroupedItemList.GroupedItemListApi'); ?>
<? $itemsWidget->itemsForActionApi($productListResultFilter, $categoryRoots) ?>

<div class="col-xs-24 col-md-24">
    <a href="<?= $action->getUrlPage(); ?>/catalog" class="button show-all-items">Смотреть все товары</a>
</div>

<? if (!empty($root)): ?>
    <? $this->renderPartial('/blocks/_itemList', [
        'type' => 'top20',
        'showAllUrl' => false,
        'title' => 'Товары, участвующие в акции',
        'categoryId' => $root->category_id,
        'count' => 10,
    ]) ?>
<? endif ?>
<? if (!empty($root) || !empty($action->getCondition())): ?>
    <div id="main_accordeon" aria-multiselectable="true" role="tablist" class="blocks_accordeon row">
        <? if (!empty($action->getCondition())): ?>
        <div class="panel panel-default blocks_accordeon__item col-xs-24">
            <div role="tab" id="rules-content" class="panel-heading blocks_accordeon__heading">
                <div class="panel-title blocks_accordeon__title color_5">
                    <a data-toggle="collapse" data-parent="#main_accordeon" href="#main_accordeon_conditions"<? if(isset($_GET['rules']) && $_GET['rules'] == 'open'): ?> aria-expanded="true"<? else: ?> class="collapsed"<? endif ?> aria-controls="main_accordeon1">
                        <div class="col-xs-22 col-md-23 col-xs-offset-1 col-md-offset-0">
                            Полные условия акции<span class="blocks_accordeon__sign"></span>
                        </div>
                    </a>
                </div>
                <div id="main_accordeon_conditions" role="tabpanel" aria-labelledby="rules-content" class="panel-collapse collapse<? if(isset($_GET['rules']) && $_GET['rules'] == 'open'): ?> in<? endif ?>">
                    <div class="panel-body blocks_accordeon__body">
                        <div class="col-xs-24">
                            <?= $action->getCondition(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <? endif ?>
        <div class="clearfix"></div>
    </div>
<? endif ?>