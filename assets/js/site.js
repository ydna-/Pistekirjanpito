$(document).ready(function () {
    $("#nExercises").change(function () {
        $("#starExercises").children().remove();
        for (var i = 1; i <= $(this).val(); i++) {
            $("#starExercises").append('<option value=' + i + '>' + i + '</option>');
        }
        $("#divStar").show();
    });
    $(".custom-checkbox").val("").click(function () {
        switch($(this).val()) {
            case "":
                $(this).val("V");
                $(this).prop('indeterminate',true);
                break;
            case "V":
                $(this).val("O");
                $(this).prop('indeterminate',false);
                $(this).prop('checked',true);
                break;
            case "O":
                $(this).val("");
                $(this).prop('indeterminate',false);
                $(this).prop('checked',false);
        }
    });
});