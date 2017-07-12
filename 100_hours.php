<? Yii::app()->less->register('actions'); ?>
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
        "title" => CHtml::encode($action->getNameList()),
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
<? if ($productList->hasItems()): ?>
    <div class="action-space action-space--8">
        <div class="content-area">
            <div id="category-list" class="category-list-title">
                ТОВАРЫ, УЧАСТВУЮЩИЕ В АКЦИИ
            </div>
        </div>
        <div class="content-area">
            <div class="subcategory grid">
                <? $displayItems = array_slice($productList->getItems(), 0, 8); ?>
                <? $this->renderPartial('blocks/api_itemsList', [
                    'items' => $displayItems,
                    'force_category' => $force_category
                ]) ?>
                <div class="clear"></div>

            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="content-area">
        <a href="<?= $action->getUrlPage(); ?>/catalog" class="button show-all-items">Смотреть все товары</a>
    </div>

<? endif; ?>

<? if (!empty($action->getCondition())): ?>
    <div
        class="action-rules content-area<? if ($action->condition && isset($_GET['rules']) && $_GET['rules'] == 'open'): ?> open<? endif; ?>">
        <a href="#" class="rules-caption"><span class="dotted">Полные условия акции</span></a>
        <div id="rules-content">
            <?= $action->getCondition(); ?>
        </div>
    </div>
<? endif; ?>