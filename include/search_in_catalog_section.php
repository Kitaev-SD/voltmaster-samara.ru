<?php
/**
 * @var $arParams
 */
?>
<div class="">
    <div class="section-search__container search-wrapper">
        <form class="search section-search__form section-search__content__form" action="<?= $arParams['FORM_ACTION'] ?>" method="get" style="width: 236px;">
            <div class="search-input-div">
                <input id="section-search__query"
                       class="search-input section-search__content__query"
                       type="search"
                       name="search_query"
                       value="<?= $arParams['DEFAULT_VALUE'] ?>"
                       placeholder="<?= $arParams['SEARCH_INPUT_PLACEHOLDER'] ?>">
            </div>
            <input id="section-id" type="hidden" name="section_search" value="<?= $arParams['SECTION_ID'] ?>">
            <div class="search-button-div">
                <button class="section-search__form-submit btn btn-search" type="submit" value="Найти"><i class="svg svg-search svg-black"></i></button>
            </div>
        </form>
        <div class="section-search__hint">
            <div class="section-search__hint-list"></div>
        </div>
        <span class="section-search__content__form__mobile">Введите поисковый запрос и нажмите кнопку "Найти".</span>
    </div>
</div>
<br>