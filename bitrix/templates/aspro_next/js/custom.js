/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/

$(function () {
    var $searchForm = $('.section-search__form');

    $("#section-search__query").keyup(function (I) {
        switch (I.keyCode) {
            case 13:
                $('.section-search__form-submit').trigger('click');
                break;
            case 27:
            case 38:
            case 40:
                break;

            default:

                if ($(this).val().length > 2) {
                    $.ajax({
                        url: '/include/search_in_section_hints.php',
                        method: $searchForm.attr('method'),
                        dataType: 'json',
                        data: $searchForm.serialize(),
                        success: function (response) {
                            var hintsTemplate = '';
                            var $found = $('#found-elements');
                            var elements = '';

                            $.each(response, function (key, item) {
                                elements += item['ITEM_ID'] + ' ';
                                hintsTemplate +=
                                    '<a href="' + item['URL'] + '" class="section-search__hint-item">' +
                                        '<span class="section-search__hint-item__img"><img src="' + item['PICTURE'] + '" alt=""></span>' +
                                        '<span class="section-search__hint-item__text">' + item['ITEM_NAME'] + '</span>' +
                                    '</a>';
                            });

                            $('.section-search__hint-list').html(hintsTemplate);
                            var addr = window.location.pathname;

                            $('.section-search__hint-list a:last-child').after('<a href="#" class="search_btn">Все результаты</a>');

                            (function () {
                                setTimeout(function(){
                                    var val = $('#section-search__query').val();
                                    $('.search_btn').attr('href', addr + '/?q='+val);
                                }, 10);
                            }());

                            if(response.length) {
                                $('.section-search__hint').show();
                                $found.val(elements);
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
    function getBaseUrl() { return window.location.href.match(/^.*\//);
    }
    $('html').on('click', function () {
        $('.section-search__hint').hide();
    });

    // $('.section-search__form-submit').on('click', function (e) {
    //     e.preventDefault();
    //
    //     $.ajax({
    //         url: $searchForm.attr('action'),
    //         method: $searchForm.attr('method'),
    //         data: $searchForm.serialize(),
    //         success: function (response) {
    //             console.log(response);
    //         }
    //     });
    // });

    // Скрываем количество найденных товаров в поиске по каталогу
    /*
    {
        const text = document.querySelector('.ajax_load').childNodes[0];
        if (text.nodeName === '#text') {
            text.nodeValue = '';
        }
    };
    */
});