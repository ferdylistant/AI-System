
function loadTodoList(todolistPage,tab) {
    if (tab) {
        // console.log(tab);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/api/home/" + tab + "?page=" + todolistPage,
            beforeSend: function() {
                $('#todoList_data').parent().addClass('card-progress')
            },
            success: function(result) {
                // console.log(tab)
                $('#count' + tab).text(result.count);
                $('#' + tab).append(result.html);
                $('[data-toggle="popover"]').popover();
                $('.scroll-down').animate({ scrollTop: 0 }, 800,"swing");
            },
            error: function(err) {
                // console.log(err)
                notifToast('error', 'Gagal memuat todo-list!');
            },
            complete: function() {
                $('#todoList_data').parent().removeClass('card-progress')
            }
        });
    }

}
async function loadRecentData(page) {
    cardWrap = $(".section-body").find('#card_recent_activity').closest(".card");
    $.ajax({
        url: window.location.origin + "/api/home/recent-activity?page=" + page,
        type: "get",
        datatype: "html",
        beforeSend: function() {
            cardWrap.addClass('card-progress');
        }
    }).done(function(data) {
        // console.log(data.length);
        cardWrap.removeClass('card-progress');
        $('.scroll-index').animate({ scrollTop: 0 }, 800,"swing");
        if (data.exist === false) {
            notifToast('error', 'Tidak ada data lagi!');
        } else {
            $("#recentActivity").append(data.html);
        }
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
        notifToast('error', 'Terjadi kesalahan!')
    });
}
function timelineFunction() {
    $('#timelineNaskah').horizontalTimeline({
        // ! Deprecated in favour of the object options. //
        desktopDateIntervals: 200, //************\\
        tabletDateIntervals: 150, // Minimum: 120 \\
        mobileDateIntervals: 120, //****************\\
        minimalFirstDateInterval: true,
        dateIntervals: {
            "desktop": 200, //************\\
            "tablet": 150, // Minimum: 120 \\
            "mobile": 120, //****************\\
            "minimal": true
        },

        /* End new object options */

        dateDisplay: "dateTime", // dateTime, date, time, dayMonth, monthYear, year
        dateOrder: "normal", // normal, reverse

        autoplay: false,
        autoplaySpeed: 8, // Sec
        autoplayPause_onHover: false,

        useScrollWheel: false,
        useTouchSwipe: true,
        useKeyboardKeys: true,
        addRequiredFile: true,
        useFontAwesomeIcons: true,
        useNavBtns: true,
        useScrollBtns: true,

        // ! Deprecated in favour of the object options. //
        iconBaseClass: "fas fa-3x", // Space separated class names
        scrollLeft_iconClass: "fa-chevron-circle-left",
        scrollRight_iconClass: "fa-chevron-circle-right",
        prev_iconClass: "fa-arrow-circle-left",
        next_iconClass: "fa-arrow-circle-right",
        pause_iconClass: "fa-pause-circle",
        play_iconClass: "fa-play-circle",

        animation_baseClass: "animationSpeed", // Space separated class names
        enter_animationClass: {
            "left": "enter-left",
            "right": "enter-right"
        },
        exit_animationClass: {
            "left": "exit-left",
            "right": "exit-right"
        },
        iconClass: {
            "base": "fas fa-3x", // Space separated class names
            "scrollLeft": "fa-chevron-circle-left",
            "scrollRight": "fa-chevron-circle-right",
            "prev": "fa-arrow-circle-left",
            "next": "fa-arrow-circle-right",
            "pause": "fa-pause-circle",
            "play": "fa-play-circle"
        },
        animationClass: {
            "base": "animationSpeed", // Space separated class names,
            "enter": {
                "left": "enter-left",
                "right": "enter-right"
            },
            "exit": {
                "left": "exit-left",
                "right": "exit-right"
            }
        }

        /* End new object options */
    });
}
$(document).ready(function() {
    $('.counter').each(function() {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 3000,
            easing: 'swing',
            step: function(now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
    $('[name="tb_naskah_tahapan"]').val('').change();
    $(".select2-timeline")
        .select2({
            placeholder: "Pilih Timeline",
        });
    $(".select2-judul")
        .select2({
            placeholder: "Pilih Judul",
            ajax: {
                url: window.location.origin + '/api/home/select-judul',
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        term: params.term
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.judul_final.charAt(0).toUpperCase() + item.judul_final.slice(1),
                                id: item.judul_final,
                            };
                        }),
                    };
                },
            },
        });
    $(".select2-penulis")
        .select2({
            placeholder: "Pilih Penulis",
        });
    $(".select2-kategori")
        .select2({
            placeholder: "Pilih Kategori",
        });
    $('#container_tb_naskah').on('change', '[name="tb_naskah_tahapan"]', function(e) {
        // console.log($(this).val());
        let id = $(this).val();
        let cardWrap = $(".section-body").find(this).closest(".card");
        $.ajax({
            url: window.location.origin + "/timeline/show",
            type: 'POST',
            data: {
                id: id,
            },
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                cardWrap.addClass('card-progress');
            },
            success: function(result) {
                // console.log(result);

                if (result.status == 'error') {
                    notifToast(result.error, 'Belum ada progress!')
                    $('#showTimeline').html(`<div class="col-12 offset-3 mt-5">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" width="100%">
                            </div>
                        </div>
                    </div>`);
                } else {
                    $('#showTimeline').html(result.data);
                    timelineFunction();
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                notifToast('error', 'Terjadi kesalahan!')
            },
            complete: function() {
                cardWrap.removeClass('card-progress');
            }
        });
    });
    var semuaPage = 1;
    var belumPage = 1;
    var selesaiPage = 1;
    loadTodoList(semuaPage,$('#todoList_data .nav-link.active').data('typeget'));
    // Each change tab form penilaian
    $(document).on('show.bs.tab', function(e) {
        let tab = $(e.delegateTarget.activeElement).data('typeget');
        if (tab === 'selesai') {
            loadTodoList(selesaiPage,tab);
        } else if (tab === 'belum-selesai') {
            loadTodoList(belumPage,tab);
        } else {
            loadTodoList(semuaPage,tab);
        }
    });
    var page = 1;
    let timeline = $('[name="timeline"]').val();
    if (timeline == true) {
        loadRecentData(page);
    }
    $("#loadScroll").scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            page++;
            loadRecentData(page);
        }
    });
    $("#myTabContent").scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            if($('#todoList_data .nav-link.active').data('typeget') === 'selesai') {
                selesaiPage++;
                loadTodoList(selesaiPage,$('#todoList_data .nav-link.active').data('typeget'));
            } else if($('#todoList_data .nav-link.active').data('typeget') === 'belum-selesai') {
                belumPage++;
                loadTodoList(belumPage,$('#todoList_data .nav-link.active').data('typeget'));
            }  else {
                semuaPage++;
                loadTodoList(semuaPage,$('#todoList_data .nav-link.active').data('typeget'));
            }
        }
    });
    $('#todoList_data').on('click', '.delete-todo', function() {
        var id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: window.location.origin + "/api/home/delete-todo",
            data: ({
                id: id
            }),
            success: function(html) {
                // console.log(html);
                $(".delete_list_" + id).fadeOut(300, function() {
                    $(".delete_list_" + id).remove();
                });
            },
            error: function(err) {
                notifToast('error', 'Terjadi kesalahan!')
            }
        });
    });
});
