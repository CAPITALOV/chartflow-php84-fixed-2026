/**
 * Created by apple on 18.09.15.
 */


function toggleChart(chartItemId){
    if ($("#"+chartItemId).hasClass('hide')) {
        $("#"+chartItemId).removeClass("hide");
    } else {
        $("#"+chartItemId).addClass("hide");
    }
}