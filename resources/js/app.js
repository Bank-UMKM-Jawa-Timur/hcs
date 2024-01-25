// modal
$("[data-modal-toggle]").on("click", function () {
    $(".modal").css("animation", "swipe-in 0.4s ease-in-out");
    $(".modal-layout").css(
        "animation",
        "opacity-in 0.2s cubic-bezier(0.17, 0.67, 0.83, 0.67)"
    );
    const modalId = $(this).data("modal-id");
    $("#" + modalId).removeClass("hidden");
});

$(".modal-layout").click(function (e) {
    if (!$(this).hasClass("no-backdrop-click")) {
        if (e.target.closest(".modal")) return;
        setTimeout(function () {
            $(".modal").css("animation", "swipe-out 0.2s ease-in-out");
            $(".modal-layout").css(
                "animation",
                "opacity-out 0.2s cubic-bezier(0.17, 0.67, 0.83, 0.67)"
            );
        }, 200);
        setTimeout(function () {
            $(".modal-layout").addClass("hidden");
        }, 400);
    }
});

$(document).keyup(function (e) {
    if (e.key === "Escape") {
        setTimeout(function () {
            $(".modal").css("animation", "swipe-out 0.2s ease-in-out");
            $(".modal-layout").css(
                "animation",
                "opacity-out 0.2s cubic-bezier(0.17, 0.67, 0.83, 0.67)t"
            );
        }, 200);
        setTimeout(function () {
            $(".modal-layout").addClass("hidden");
        }, 400);
    }
});
$("[data-modal-dismiss]").on("click", function () {
    const dismissId = $(this).data("modal-dismiss");
    setTimeout(function () {
        $(".modal").css("animation", "swipe-out 0.2s ease-in-out");
        $(".modal-layout").css(
            "animation",
            "opacity-out 0.2s cubic-bezier(0.17, 0.67, 0.83, 0.67)"
        );
    }, 200);
    setTimeout(function () {
        $("#" + dismissId).addClass("hidden");
    }, 400);
});

$(".btn-tab").on("click", function () {
    $(".btn-tab").removeClass("active-tab");
    $(this).addClass("active-tab");
    $(".tab-content").addClass("hidden");
    $("#" + $(this).data("tab")).removeClass("hidden");
});

let interval = setInterval(function () {
    var dateNow = dayjs().format("DD MMMM YYYY");
    var clockNow = dayjs().format("HH:mm:ss");
    $("#date").text(dateNow);
    $("#clock").text(clockNow);
}, 1000);

$(".dropdown-account").on("click", function (e) {
    $(".dropdown-account-menu").toggleClass("hidden");
    e.stopPropagation();
});
$(document).click(function (e) {
    if (e.target.closest(".dropdown-account-menu")) return;
    $(".dropdown-account-menu").addClass("hidden");
});

$('.btn-scroll-to-top').on('click', function () {
    $('#scroll-body').animate({
        scrollTop: $("#scroll-body").offset().top
    }, 400);
});

$('#scroll-body').scroll(function () {
    if ($('#scroll-body').scrollTop() > 400) {
        $(".btn-scroll-to-top").removeClass("hidden");
    } else {
        $(".btn-scroll-to-top").addClass("hidden");
    }
});
