<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?>

<?/*$APPLICATION->IncludeComponent("bitrix:main.profile","",Array(
        "USER_PROPERTY_NAME" => "",
        "SET_TITLE" => "Y",
        "AJAX_MODE" => "N",
        "USER_PROPERTY" => Array(),
        "SEND_INFO" => "Y",
        "CHECK_RIGHTS" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    )
);*/?>

<style type="text/css">
    .section-search__container {
        position: relative;
    }

    .section-search__hint {
        display: none;
        position: absolute;
        background: #FFF;
        width: 100ch;
        border-bottom: 1px solid #f6f6f6;
        z-index: 1000;
    }

    .section-search__hint-item {
        display: block;
    }
</style>

<div class="container">
    <div class="section-search__container">
        <form class="section-search__form" action="/catalog/" method="get">
            <input id="section-search__query" type="search" name="q" placeholder="Поиск по разделу">
            <input type="hidden" name="section_search" value="223">
            <button class="section-search__form-submit" type="submit">Найти</button>
        </form>
        <div class="section-search__hint">
            <div class="section-search__hint-list"></div>
            <div class="section-search__hint-all">
                <a class="section-search__hint-all__link" href="">Все результаты</a>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    $(function () {
        $("#section-search__query").keyup(function (I) {
            switch (I.keyCode) {
                case 13:
                case 27:
                case 38:
                case 40:
                    break;

                default:

                    if ($(this).val().length > 2) {

                        var $searchForm = $('.section-search__form');
                        var allSearchUrl = $searchForm.attr('action') + '?' + $(this).attr('name') + '=' + $(this).val();

                        $('.section-search__hint-all__link').attr('href', allSearchUrl);

                        $.ajax({
                            url: $searchForm.attr('action'),
                            method: $searchForm.attr('method'),
                            dataType: 'json',
                            data: $searchForm.serialize(),
                            success: function (response) {
                                var hintsTemplate = '';

                                $.each(response, function (key, item) {
                                    hintsTemplate +=
                                        '<a href="' + item['URL'] + '" class="section-search__hint-item">' +
                                            '<img src="' + item['PICTURE'] + '" alt="">' +
                                            '<span>' + item['ITEM_NAME'] + '</span>' +
                                        '</a>';
                                });

                                $('.section-search__hint-list').html(hintsTemplate);
                                if(response.length) {
                                    $('.section-search__hint').show();
                                } else {
                                    $('.section-search__hint').hide();
                                }

                            }
                        });

                        return false;

                    }

                    break;
            }
        });

        $('html').on('click', function () {
            $('.section-search__hint').hide();
        });
    });
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>