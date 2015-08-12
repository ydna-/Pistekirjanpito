function showPoints(elem) {
    $(".div-exercise").each(function () {
        $(this).hide();
    });
    $("#div" + elem.id).show();
}

function toggle(elem) {
    if (elem.hasClass("star-checkbox")) {
        switch (elem.val()) {
            case "":
                elem.val("V");
                elem.prop('indeterminate', true);
                break;
            case "V":
                elem.val("O");
                elem.prop('indeterminate', false);
                elem.prop('checked', true);
                break;
            case "O":
                elem.val("");
                elem.prop('indeterminate', false);
                elem.prop('checked', false);
                break;
            default:
                return;
        }
    } else if (elem.hasClass("ns-checkbox")) {
        switch (elem.val()) {
            case "":
                elem.val("O");
                elem.prop('checked', true);
                break;
            case "O":
                elem.val("");
                elem.prop('checked', false);
                break;
            default:
                return;
        }
    }
}

function selectAll() {
    $(".ns-checkbox").each(function () {
        $(this).val("O");
        $(this).prop('checked', true);
    });
    $(".star-checkbox").each(function () {
        $(this).val("O");
        $(this).prop('indeterminate', false);
        $(this).prop('checked', true);
    });

}

function removeAll() {
    $(".ns-checkbox").each(function () {
        $(this).val("");
        $(this).prop('checked', false);
    });
    $(".star-checkbox").each(function () {
        $(this).val("");
        $(this).prop('indeterminate', false);
        $(this).prop('checked', false);
    });
}

$(document).ready(function () {
    $('form.destroy-form').on('submit', function (submit) {
        var confirm_message = $(this).attr('data-confirm');
        if (!confirm(confirm_message)) {
            submit.preventDefault();
        }
    });
    $("#nExercises").change(function () {
        $("#starExercises").children().remove();
        for (var i = 1; i <= $(this).val(); i++) {
            $("#starExercises").append('<option value=' + i + '>' + i + '</option>');
        }
        $("#divStar").show();
    });
    $(".ns-checkbox").val("").click(function () {
        switch ($(this).val()) {
            case "":
                $(this).val("O");
                $(this).prop('checked', true);
                break;
            case "O":
                $(this).val("");
                $(this).prop('checked', false);
        }
    });
    $(".star-checkbox").val("").click(function () {
        switch ($(this).val()) {
            case "":
                $(this).val("V");
                $(this).prop('indeterminate', true);
                break;
            case "V":
                $(this).val("O");
                $(this).prop('indeterminate', false);
                $(this).prop('checked', true);
                break;
            case "O":
                $(this).val("");
                $(this).prop('indeterminate', false);
                $(this).prop('checked', false);
        }
    });
    $("#selCourseNo").change(function () {
        var student_no = $(this).val();
        $(".checkbox").each(function (i) {
            var problem_no = $(this).attr('name');
            var temp = student_no + "|" + problem_no;
            switch ($(document.getElementById(temp)).val()) {
                case " ":
                    $(this).val("");
                    $(this).prop('indeterminate', false);
                    $(this).prop('checked', false);
                    break;
                case "O":
                    $(this).val("O");
                    $(this).prop('indeterminate', false);
                    $(this).prop('checked', true);
                    break;
                case "V":
                    $(this).val("V");
                    $(this).prop('indeterminate', true);
                    break;
                default:
                    $(this).val("");
                    $(this).prop('indeterminate', false);
                    $(this).prop('checked', false);
            }
        });
    });
});

$(document).keydown(function (e) {
    switch (e.which) {
        case 49:
            toggle($("#p1"));
            break;
        case 50:
            toggle($("#p2"));
            break;
        case 51:
            toggle($("#p3"));
            break;
        case 52:
            toggle($("#p4"));
            break;
        case 53:
            toggle($("#p5"));
            break;
        case 54:
            toggle($("#p6"));
            break;
        case 55:
            toggle($("#p7"));
            break;
        case 56:
            toggle($("#p8"));
            break;
        case 57:
            toggle($("#p9"));
            break;
        case 48:
            toggle($("#p10"));
            break;
        case 81:
            toggle($("#p11"));
            break;
        case 87:
            toggle($("#p12"));
            break;
        case 69:
            toggle($("#p13"));
            break;
        case 82:
            toggle($("#p14"));
            break;
        case 84:
            toggle($("#p15"));
            break;
        case 89:
            toggle($("#p16"));
            break;
        case 85:
            toggle($("#p17"));
            break;
        case 73:
            toggle($("#p18"));
            break;
        case 79:
            toggle($("#p19"));
            break;
        case 80:
            toggle($("#p20"));
            break;
        default:
            return;
    }
});