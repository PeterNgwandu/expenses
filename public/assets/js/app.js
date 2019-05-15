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

$(function() {
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
    var url = 'journal/report/' + journal_no + '/' + currency;
    $.get(url, function(data) {

    });
});

$(document).on('click', '.print-requisition', function() {
    var req_no = $(this).attr('data-value');
    var url = '/requisition/report/' + req_no;
    $.get(url, function(data) {

    });
});

$(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    var url = '/delete-deparment/' + id;
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
                data: { id: id },
                success: function(data) {
                    $('.swal2-confirm').click(function() {
                        window.location.href = '/delete-deparment/' + id;
                    });
                }
            });
        });
});

$(document).on('change', '#budget', function() {
    var budget_id = $(this).val();
    var url = 'get_next_item_no_by_budget_id/' + budget_id;
    $.get(url, function(data) {
        // console.log(data.result);
        $('#item_no').val(data.result);
    });
});

$(document).on('change', '#budget2', function() {
    var budget_id = $(this).val();
    var url = 'get_next_item_no_by_budget_id_two/' + budget_id;
    $.get(url, function(data) {
        // console.log(data.result);
        $('#item_no2').val(data.result);
    });
});

$(document).on('click', '.delete-user', function() {
    var currentRow = $(this);
    var user_id = $(this).attr('id');
    var url = 'delete-user/' + user_id;
    $.get(url, function(data) {
        console.log(data.result);
        currentRow.parent().parent().remove();
        swal("Deleted!", "User has been deleted successfuly.", "success");
    });
});

$(document).on('click', '.delete-retirement-line', function(e) {
    e.preventDefault();
    var currentRow = $(this);
    var ret_id = $(this).attr('id');
    var url = '/delete-retirement/' + ret_id;

    $.get(url, function(data) {
        console.log(data.result);
        currentRow.parent().parent().remove();
        swal("Deleted!", "Your retirement line has been deleted successfuly.", "success");
    });

});

$(document).on('click', '.delete-requisition-by-id', function(e) {
    e.preventDefault();
    var currentRow = $(this);
    var req_id = $(this).attr('id');
    var url = '/delete-requsition-by-id/' + req_id;

    $.get(url, function(data) {
        console.log(data.result);
        currentRow.parent().parent().remove();
        swal("Deleted!", "Your requisition line has been deleted successfuly.", "success");
    });

});

$(document).on('click', '.database-backup', function(e) {
    e.preventDefault();
    var url = '/database-backup';
    $.get(url, function(data) {
        console.log(data.result);
        swal("Good Job", "Database backup created successfuly", "success");
    });
});

/* Inline Editing of the requisition lines on temporary table */

$(document).on('keyup', '#temp_item_name', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var item_name = $tr.find('#temp_item_name').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-item-name/' + data_id + '/' + item_name;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#temp_unit_measure', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var unit_measure = $tr.find('#temp_unit_measure').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-unit-measure/' + data_id + '/' + unit_measure;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#temp_quantity', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var quantity = $tr.find('#temp_quantity').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-quantity/' + data_id + '/' + quantity;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#temp_unit_price', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var unit_price = $tr.find('#temp_unit_price').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-unit-price/' + data_id + '/' + unit_price;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#temp_vat', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var vat = $tr.find("#temp_vat").val();
    var data_id = $(this).attr('data-id');
    var url = '/update-vat/' + data_id + '/' + vat;
    // $.get(url, function(data) {
    //     console.log(data.result);
    // });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: '/update-vat/' + data_id + '/' + vat,
        data: $('#temp_vat').serialize() + "&" + $.param({ 'vat': vat }),
        dataType: "json",
        success: function(data) {
            console.log(data.result);
        },
        error: function() {
            //alert('opps error occured');
        }
    });

});

$(document).on('keyup', '#temp_description', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var description = $tr.find('#temp_description').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-description/' + data_id + '/' + description;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#temp_item_id', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var budget_line = $tr.find('#temp_item_id').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-budget-line/' + data_id + '/' + budget_line;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#temp_account', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var account = $tr.find('#temp_account').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-account/' + data_id + '/' + account;
    $.get(url, function(data) {
        console.log(data.result);
    })
});

// Inline Editing of the Requisitions for Requisition with Budgets

$(document).on('keyup', '#perm_item_name', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var item_name = $tr.find('#perm_item_name').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-item-name/' + data_id + '/' + item_name;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#perm_unit_measure', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var unit_measure = $tr.find('#perm_unit_measure').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-unit-measure/' + data_id + '/' + unit_measure;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#perm_quantity', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var quantity = $tr.find('#perm_quantity').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-quantity/' + data_id + '/' + quantity;
    $.get(url, function(data) {
        console.log(data.result);
        console.log(data.gross_amount);
        document.getElementById("total").innerHTML = data.gross_amount;
    });
});

$(document).on('keyup', '#perm_unit_price', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var unit_price = $tr.find('#perm_unit_price').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-unit_price/' + data_id + '/' + unit_price;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#perm_description', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var description = $tr.find('#perm_description').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-description/' + data_id + '/' + description;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#perm_item_id', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var item_id = $tr.find('#perm_item_id').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-item_id/' + data_id + '/' + item_id;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#perm_vat', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var vat = $tr.find('#perm_vat').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-vat/' + data_id + '/' + vat;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#perm_account', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var account = $tr.find('#perm_account').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-account/' + data_id + '/' + account;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

// Inline Editing of the Requisitions for Requisition with no Budgets

$(document).on('keyup', '#no_budget_perm_item_name', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var item_name = $tr.find('#no_budget_perm_item_name').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-with-no-budget-item-name/' + data_id + '/' + item_name;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#no_budget_perm_unit_measure', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var unit_measure = $tr.find('#no_budget_perm_unit_measure').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-with-no-budget-unit_measure/' + data_id + '/' + unit_measure;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#no_budget_perm_quantity', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var quantity = $tr.find('#no_budget_perm_quantity').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-with-no-budget-quantity/' + data_id + '/' + quantity;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#no_budget_perm_unit_price', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var unit_price = $tr.find('#no_budget_perm_unit_price').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-with-no-budget-unit_price/' + data_id + '/' + unit_price;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('keyup', '#no_budget_perm_description', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var description = $tr.find('#no_budget_perm_description').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-with-no-budget-description/' + data_id + '/' + description;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#no_budget_perm_vat', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var vat = $tr.find('#no_budget_perm_vat').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-vat/' + data_id + '/' + vat;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('change', '#no_budget_perm_account', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var account = $tr.find('#no_budget_perm_account').val();
    var data_id = $(this).attr('data-id');
    var url = '/update-requisition-account/' + data_id + '/' + account;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('click', '.submit-new-requisition', function(e) {
    e.preventDefault();


    localStorage.setItem("budget_id", $(this).closest('form').find('select[name=budget_id]').val());
    if (localStorage.getItem("budget_id")) {
        $(this).closest('form').find('select[name=budget_id]').val(localStorage.getItem("budget_id"));
        $("#budget").val(localStorage.getItem("budget_id"));
        console.log(localStorage.getItem("budget_id"));
    }

    var budget_id = $(this).closest('form').find('input[name=budget_id]').val();
    var item_id = $(this).closest('form').find('select[name=item_id]').val();
    var req_no = $(this).closest('form').find('input[name=req_no]').val();
    var activity_name = $(this).closest('form').find('input[name=activity_name]').val();

    var item_name2 = $(this).closest('form').find('input[name=item_name]').val();
    var unit_measure = $(this).closest('form').find('input[name=unit_measure]').val();
    var unit_price = $(this).closest('form').find('input[name=unit_price]').val();
    var quantity = $(this).closest('form').find('input[name=quantity]').val();
    var vat = $(this).closest('form').find('select[name=vat]').val();
    var account_id = $(this).closest('form').find('select[name=account_id]').val();
    var description = $(this).closest('form').find('input[name=description]').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: '/submit-new-single-requisition-row',
        data: $('.requisition-form').serialize() + "&" + $.param({ 'req_no': req_no, 'budget_id': budget_id, 'item_id': item_id, 'activity_name': activity_name, 'item_name2': item_name2, 'unit_measure': unit_measure, 'unit_price': unit_price, 'quantity': quantity, 'vat': vat, 'description': description, 'account_id': account_id }),
        dataType: "json",
        success: function(data) {
            console.log(data.result);
            $('.render-new-requisition').html(data.result);
            swal("Confirmed", "Requisition line created successfuly.", "success");
            if (budget_id == localStorage.getItem("budget_id")) {
                $(this).closest('form').find('select[name=budget_id]').attr('selected', true);
            }
            $("#data").find("#activity_name").val('');
            $("#data").find("#item_name2").val('');
            $("#data").find("#unit_measure").val('');
            $("#data").find("#quantity").val('');
            $("#data").find("#unit_price").val('');
            $("#data").find("#description").val('');
        },
        error: function() {
            //alert('opps error occured');
        }
    });

});

$(document).on('click', '.deleting-requisition', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var currentRow = $(this);
    var req_no = $(this).attr('data-id');
    var url = '/deleting-requisition/' + req_no + '/' + id;
    $.get(url, function(data) {
        console.log(data.result);
        currentRow.parent().parent().remove();
        swal("Confirmed", "Requisition line deleted successfuly.", "success");
    });
});

$(document).on('click', '.approve-budget', function(e) {
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    var url = '/approve-budget/' + data_id;
    $.get(url, function(data) {
        console.log(data.result);
        // swal("Good Job", "Budget has been approved successfuly", "success");
        window.location = '/budgets/create';
    });
});

$(document).on('click', '.reject-budget', function(e) {
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    var url = '/reject-budget/' + data_id;
    $.get(url, function(data) {
        console.log(data.result);
        // swal('Good Job', 'Budget has been rejected succssfuly', 'success');
        window.location = '/budgets/create';
    });
});

$(document).on('click', '.delete-budget', function(e) {
    e.preventDefault();
    var currentRow = $(this);
    var data_id = $(this).attr('data-id');
    var url = '/delete-budget/' + data_id;
    $.get(url, function(data) {
        console.log(data.result);
        swal('Good Job', 'Budget deleted successfuly', 'success');
        currentRow.parent().parent().remove();
    });
});

$(document).on('keyup', '.adjust-limit', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var max_amount = $(this).attr('data-value');
    alert(max_amount);
    var url = '/adjust-limit/' + id + '/' + max_amount;
    $.get(url, function(data) {
        console.log(data.result);
    });
});

$(document).on('click', '.enable-edit-requisition-line', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");

    $('.budget_line').prop("disabled", false);
    // $tr.find('.activity_name').prop("disabled", false);
    $('.item_name').prop("disabled", false);
    $('.unit_measure').prop("disabled", false);
    $('.quantity').prop("disabled", false);
    $('.unit_price').prop("disabled", false);
    $('.vat').prop("disabled", false);
    $('.account').prop("disabled", false);
    $('.description').prop("disabled", false);

    $('.budget_line').css({"border" : "1px solid #CD5C5C"});
    // $tr.find('.activity_name').css({"border" : "1px solid #CD5C5C"});
    $('.item_name').css({"border" : "1px solid #CD5C5C"});
    $('.unit_measure').css({"border" : "1px solid #CD5C5C"});
    $('.quantity').css({"border" : "1px solid #CD5C5C"});
    $('.unit_price').css({"border" : "1px solid #CD5C5C"});
    $('.vat').css({"border" : "1px solid #CD5C5C"});
    $('.account').css({"border" : "1px solid #CD5C5C"});
    $('.description').css({"border" : "1px solid #CD5C5C"});

    // $(".enable-edit-requisition-line").css({"display" : "none"});
    $(".save-requisition-line").show();
    $(".save-requisition-line").css({"cursor" : "pointer"});

    var req_no = $(this).attr("requisition-number");
    var budget_id = $tr.find("#perm_budget_id").val();
    var budget_id_no_budget = $tr.find("#no_budget_perm_budget_id").val();
    var url = '/edit-requisition-line/' + req_no;
    $.get(url, function(data) {
        console.log(data.result);
        window.location = '/edit-requisitions/' + req_no;
    });

});

$(document).on('click', '.reset', function(e) {
    e.preventDefault();
    var user_id = $(this).attr('data-value');
    var req_no = $(this).attr('req-no');
    var url = '/truncate-edited-lines/' + user_id;
    $.get(url, function(data) {
        console.log(data.result);
        // swal('', 'Data has been reset', 'success');
        window.location = '/edit-requisitions/' + req_no;
    });
});

$(document).on('click', '.reset-back', function(e) {
    e.preventDefault();
    var user_id = $(this).attr('data-value');
    var req_no = $(this).attr('req-no');
    var url = '/truncate-edited-lines/' + user_id;
    $.get(url, function(data) {
        console.log(data.result);
        // swal('', 'Data has been reset', 'success');
        window.location = '/submitted-requisitions/' + req_no;
    });
});

$(document).on('click', '.save-requisition-line', function(e) {
    e.preventDefault();
    var $tr = $(this).closest("tr");
    var data_no = $(this).attr('data-value');
    var data_id = $(this).attr('req-id'); // Not Used For now
    var user_id = $(this).attr('user-id');
    var url = '/bring-edited-line-to-permanent-table/' + user_id + '/' + data_no;
    $.get(url, function(data) {
        // console.log(data.result);
        window.location.href = '/submitted-requisitions/' + data_no;
        // swal('', 'Requisition line saved', 'success');
    });
});

function blink(selector){
$(selector).fadeOut('slow', function(){
    $(this).fadeIn('slow', function(){
        blink(this);
    });
});
}

blink('.blink');

// $(document).on('load', function() {
//     var status = $(this).attr("data-val");
//     alert(status);
//     if (status == 'Confirmed') {
//         $(".editBtn").hide();

//     }
// })
