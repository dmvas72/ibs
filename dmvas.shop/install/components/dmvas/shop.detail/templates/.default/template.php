<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?foreach($arResult["ITEMS"] as $arItem):?>
    <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    
        echo "<pre>";
        print_r($arItem);
        echo "</pre>";
/*
?>

    <div class="swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="b-reviews--item">
            <div class="swiper-slide-reviews--wrapper">
                <div class="swiper-slide-reviews--head">
                    <div class="swiper-slide-reviews--head-icon"><img src="<?=SITE_TEMPLATE_PATH?>/img/icon-review.svg"></div>
                    <div class="swiper-slide-reviews--head-info">
                        <div class="swiper-slide-reviews--head-name"><?=$arItem["PROPERTIES"]["NAME"]["VALUE"]?></div>
                        <div class="swiper-slide-reviews--head-date"><?=$arItem["PROPERTIES"]["DATETIME"]["VALUE"]?></div>
                    </div>
                </div>
                <div class="swiper-slide-reviews--content">
                    <div class="swiper-slide-reviews--text">
                        <p><?=$arItem["PREVIEW_TEXT"]?></p>
                    </div>
                </div>
            </div>
        </div> 
    </div>
<?*/endforeach;?>

