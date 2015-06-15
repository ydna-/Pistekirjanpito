$(document).ready(function () {
    $("#nExercises").change(function() {
        $("#starExercises").children().remove();
        for (var i = 1; i <= $(this).val(); i++) {
            $("#starExercises").append('<option value=' + i + '>' + i + '</option>');
        }
        $("#divStar").show();
    });
});
