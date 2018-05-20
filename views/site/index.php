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
$this->registerJsFile('/web/js/chart.js');
/*end_scripts_for_upload_file*/
?>


<div class="site-index">

    <h1>Анализатор доходности</h1>

    <div class="col-lg-3 left-block">
        <!--        загрузка файла-->
        <div class="row">
            <div class="col-md-12">
                <!-- D&D Zone-->
                <div id="drag-and-drop-zone" class="uploader">
                    <div>Перетащите сюда файл</div>
                    <div class="or">-или-</div>
                    <div class="browser">
                        <label>
                            <span>Нажмите для добавления файла</span>
                            <input type="file" name="file" accept="text/html"  title='Нажмите для добавления файла'>
                        </label>
                    </div>
                </div>
                <!-- /D&D Zone -->

            </div>


            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Список отчетов</h3>
                    </div>
                    <div class="panel-body list-report-panel" id='list-report-empty' style='display:none'>
                        <i>Ещё нет загруженых файлов</i>
                    </div>
                    <div id='container-reports'>
                    </div>
                </div>
            </div>
        </div>
        <!--        END^загрузка файла-->

    </div>

    <div class="main-content col-lg-9">

        <div id="container" style="height: 600px; min-width: 310px"></div>

    </div>
</div>

<script>

$(function () {
    drawChartByNameFile('1e797286951b1eac96f60973bcd534c6.html');
});

</script>
