$(document).ready(function () {
    var d = new Date();
    var canvas = document.getElementById("moderations_chart");
    var ctx = canvas.getContext("2d");
    var mode = 'month';
    var actions;
    var chartConfig = {
        responsive: true,
        onAnimationProgress: function () {
            placeChartStats();
        },
        customTooltips: function(){
            placeChartStats();
        }
    };


    function updateChartStats(payload) {
        if ("undefined" == typeof payload) {
            var payload = payload || {t: mode, d: d.getUTCDate(), m: d.getUTCMonth() + 1, Y: d.getUTCFullYear()};
            replaceLabel(payload);
        }

        return $.ajax({
            url: '/api/moderation/stats',
            type: "POST",
            data: payload,
            beforeSend: function () {
                $('#js-dp').hide();
                $('#moderations_chart').replaceWith('<canvas id="moderations_chart" data-type="Line"></canvas>');
                canvas = document.getElementById("moderations_chart");
                ctx = canvas.getContext("2d");
            }
        });
    }

    function replaceLabel(payload) {
        console.log($('#stat-label').html())
        $('#stat-label').html($('#stat-label').html().replace(/(\d{4}-\d{1,2}(-\d{1,2})?)|(\{\})$/, [payload.Y, payload.m, payload.d].join('-')));
        $('#stat-label').html($('#stat-label').html().replace(/-$/, ''));
    }

    function placeChartStats() {
        ctx.font = "20px Arial";
        var sum = 0;
            console.log(actions);
        for (i in actions){
            sum += actions[i];
        }
        ctx.fillText(sum, Math.floor(canvas.width/2), 50);
    }

    $(document).on('mousemove', '#moderations_chart', function () {
        placeChartStats();
    })


    $(document).on('change', '#js-dp', function (e) {
        var p = $(this).val().split('-');
        var data = {t: mode, d: p[2], m: p[1], Y: p[0]}
        updateChartStats(data).done(function (d) {
            new Chart(ctx).Line(d, chartConfig);
            if ('month' == mode)
                delete(data.d);
            replaceLabel(data);
            actions = d.datasets[0].data
            placeChartStats()
        });
    });

    $(document).on('click', '#dp-m,#dp-d', function (e) {
        switch ($(this).prop('id')) {
            case 'dp-m':
                mode = 'month';
                break;
            case 'dp-d':
                mode = 'day';
                break;
        }
        $('#js-dp').show();
    });
    updateChartStats().done(function (d) {
// Get the context of the canvas element we want to select
        var ctx = document.getElementById("moderations_chart").getContext("2d");
        new Chart(ctx).Line(d, chartConfig);
        actions = d.datasets[0].data;
        placeChartStats()
    })

});


