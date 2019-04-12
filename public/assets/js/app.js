! function(n) {
    function o(e) {
        if (t[e]) return t[e].exports;
        var i = t[e] = {
            i: e,
            l: !1,
            exports: {}
        };
        return n[e].call(i.exports, i, i.exports, o), i.l = !0, i.exports
    }
    var t = {};
    o.m = n, o.c = t, o.d = function(n, t, e) {
        o.o(n, t) || Object.defineProperty(n, t, {
            configurable: !1,
            enumerable: !0,
            get: e
        })
    }, o.n = function(n) {
        var t = n && n.__esModule ? function() {
            return n.default
        } : function() {
            return n
        };
        return o.d(t, "a", t), t
    }, o.o = function(n, o) {
        return Object.prototype.hasOwnProperty.call(n, o)
    }, o.p = "", o(o.s = 0)
}([function(n, o, t) {
    t(1), t(2), t(3), t(4), t(5), t(6), t(7), t(8), t(9), t(10), n.exports = t(11)
}, function(n, o) {
    $(function() {
        $('[data-toggle="tooltip"]').tooltip({
            container: "body"
        })
    }), $(function() {
        $('[data-toggle="popover"]').popover()
    }), $("#toggleAppSidebar").length && ($("#toggleAppSidebar").on("click", function(n) {
        $(".apps--sidebar").toggleClass("d-none"), $("body").css("overflow", "hidden")
    }), $("[data-toggle=apps--sidebar]").on("click", function(n) {
        $(".apps--sidebar").addClass("d-none"), $("body").css("overflow", "")
    })), $("#toggleEventsSidebar").length && ($("#toggleEventsSidebar").on("click", function(n) {
        $(".events--sidebar").toggleClass("d-none"), $("body").css("overflow", "hidden")
    }), $("[data-toggle=events--sidebar]").on("click", function(n) {
        $(".events--sidebar").addClass("d-none"), $("body").css("overflow", "")
    })), $(".dropdown.notifications ul a.nav-link").click(function(n) {
        n.stopPropagation(), $(this).tab("show")
    })
}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}, function(n, o) {}]);

$(document).ready(function() {
        $('.preload').fadeOut('3000', function() {
            $('.mydata').fadeIn('2000');
        });
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(document).on('click', '#print', function(e) {
    e.preventDefault();
    var url = 'journal/report';
    $.get(url, function(data) {

    });
});

$(document).on('click', '.print', function() {
    var journal_no = $("#journal_no").val();
    var currency = $("#currency").val();
    var url = 'journal/report/'+journal_no+'/'+currency;
    $.get(url, function(data) {

    });
});

$(document).on('click', '.delete', function (e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  var url = '/delete-deparment/'+id;
  swal({
          title: "Are you sure you want to delete this !",
          type: "error",
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes!",
          showCancelButton: true,
      },
      function() {
          $.ajax({
              type: "GET",
              url: "url",
              data: {id:id},
              success: function (data) {
                    $('.swal2-confirm').click(function(){
                        window.location.href='/delete-deparment/'+id;
                    });
                  }
          });
  });
});

$(document).on('change', '#budget', function() {
    var budget_id = $(this).val();
    var url = 'get_next_item_no_by_budget_id/'+budget_id;
    $.get(url, function(data) {
        console.log(data.result);
        $('#item_no').val(data.result);
    });
});

$(document).on('change', '#budget2', function() {
    var budget_id = $(this).val();
    var url = 'get_next_item_no_by_budget_id_two/'+budget_id;
    $.get(url, function(data) {
        console.log(data.result);
        $('#item_no2').val(data.result);
    });
});
