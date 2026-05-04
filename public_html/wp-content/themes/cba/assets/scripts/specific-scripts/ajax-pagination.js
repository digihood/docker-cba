/* Ajax pagination
=====================================*/

var i = 0;
function buttonClick() {
    document.getElementById("load-more").data = ++i;
}

(function ($) {
    var load_placehoder = $("#load-more");

    $(load_placehoder).on("click", function () {
        //add max page
        var currentPage = $("#load-more").attr("data-page");
        var newPage = parseInt(currentPage) + 1;
        var maxPage = document
            .getElementById("load-more")
            .getAttribute("data-max");
        if (newPage > maxPage) newPage = maxPage;

        //set next page offset
        if (newPage <= maxPage) {
            load_placehoder.data("data-newpage", newPage);
        }

        if (
            !load_placehoder.hasClass("loading") &&
            parseInt(currentPage) < parseInt(maxPage)
        ) {
            load_placehoder.addClass("loading").removeClass("hide");

            buttonClick();

            $.ajax({
                url: d1g1AjaxPagination.ajaxurl,
                type: "post",
                data: {
                    action: "ajax_pagination",
                    query_vars: d1g1AjaxPagination.query_vars,
                    page: newPage,
                },
                success: function (result) {
                    //append the
                    $("#posts").append(result);

                    //set new page offset
                    load_placehoder
                        .attr("data-page", newPage)
                        .removeClass("loading");

                    //skryjeme tlačítko načtení
                    if (parseInt(newPage) === parseInt(maxPage)) {
                        $("#load-more").addClass("hide");
                    }
                },
                error: function (error) {
                    console.log(error);
                },
            });
        }
    });
})(jQuery);
