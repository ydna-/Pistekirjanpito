$(document).ready(function () {
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