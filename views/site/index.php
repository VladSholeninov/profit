




<?php
/**scripts_for_graf*/
$this->registerJsFile('https://code.highcharts.com/stock/highstock.js');
$this->registerJsFile('https://code.highcharts.com/modules/series-label.js');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data-2012-2022.min.js');
$this->registerJsFile('https://code.highcharts.com/modules/drag-panes.js');
$this->registerJsFile('https://code.highcharts.com/stock/indicators/indicators.js');
$this->registerJsFile('https://code.highcharts.com/modules/data.js');
$this->registerJsFile('https://code.highcharts.com/stock/modules/exporting.js');
$this->registerJsFile('https://code.highcharts.com/stock/modules/export-data.js');
/*end_scripts_for_upload_graf*/

/**scripts_for_upload_file*/
$this->registerCssFile('/web/css/uploader.css');
$this->registerJsFile('/web/js/dmuploader.min.js');
$this->registerJsFile('/web/js/upload_file.js');
/*end_scripts_for_upload_file*/
?>


<div class="site-index">

    <h1>Профит</h1>

    <div class="col-lg-2 left-block">
        <!--        загрузка файла-->
        <div class="row">
            <div class="col-md-12">
                <!-- D&D Zone-->
                <div id="drag-and-drop-zone" class="uploader">
                    <div>перетащите сюда файл</div>
                    <div class="or">-или-</div>
                    <div class="browser">
                        <label>
                            <span>нажмите для обзора компьютера</span>
                            <input type="file" name="file" accept="text/html"  title='Click to add File'>
                        </label>
                    </div>
                </div>
                <!-- /D&D Zone -->

            </div>


            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">загруженые файлы</h3>
                    </div>
                    <div class="panel-body demo-panel-files" id='demo-files'>
                        <i>Ещё нет загруженых файлов</i>
                    </div>
                </div>
            </div>
        </div>
        <!--        END^загрузка файла-->

    </div>

    <div class="main-content col-lg-10">

        <div id="container" style="height: 600px; min-width: 310px"></div>

    </div>
</div>

<script>

$(function () {

     $.getJSON('/site/get-data', function (data) {
            // Create the chart
            Highcharts.setOptions({
                lang: {
                    loading: 'Загрузка...',
                    months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                    shortMonths: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'],
                    exportButtonTitle: "Экспорт",
                    printButtonTitle: "Печать",
                    rangeSelectorFrom: "С",
                    rangeSelectorTo: "По",
                    rangeSelectorZoom: "Период",
                    downloadPNG: 'Скачать PNG',
                    downloadJPEG: 'Скачать JPEG',
                    downloadPDF: 'Скачать PDF',
                    downloadSVG: 'Скачать SVG',
                    printChart: 'Напечатать график'
                },
                time: {
                    timezone: 'Europe/Moscow'
                }
            });

            Highcharts.stockChart('container', {

                rangeSelector: {
                    selected: 5,
                    buttons: [{
                        type: 'day',
                        count: 1,
                        text: '1д'
                    }, {
                        type: 'day',
                        count: 7,
                        text: '7д'
                    }, {
                        type: 'month',
                        count: 1,
                        text: 'мес'
                    }, {
                        type: 'all',
                        text: 'Все'
                    }]
                },

                title: {
                    text: data.name + ' (' +  data.currency + ')'
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        formatter: function() {
                            return moment(this.value).format("D.M.YY HH:MM");
                        }
                    }
                },
                navigation: {
                    buttonOptions: {
                        theme: {
                            style: {
                                color: '#E0E0E0'
                            }
                        }
                    }
                },

                series: [
                    {
                        name: 'Баланс',
                        data: data.totally_data,
                        tooltip: {
                            valueDecimals: 2
                        }
                    },{
                        name: 'profit',
                        data: data.profit_data,
                        tooltip: {
                            valueDecimals: 2
                        }
                    }
                ]
            });

        });

});
</script>
