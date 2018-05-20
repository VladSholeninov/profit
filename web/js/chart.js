
    function drawChartByNameFile(name_file)
    {
        $.getJSON({
                type: 'POST',
                data: {'name_file' : name_file},
                url: '/site/get-data'
            },
            function (result) {
                if (result.hasErrors){
                    $('#container').text(result.message);
                    bsAlert.error(result.message);
                    return false;
                }

                drawChartByData(result.full_info);
            });
    }


    function drawChartByData(data)
    {
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
                        text: data.name + ' (' +  data.currency + ')' + ' - ' + data.date
                    },
                    xAxis: {
                        type: 'datetime',
                        labels: {
                            formatter: function() {
                                return moment(this.value).format("D.M.YY HH:MM");
                            }
                        }
                    },

                    series: [
                        {
                            type: 'area',
                            name: 'Баланс',
                            data: data.totally_data,
                            tooltip: {
                                valueDecimals: 2
                            }
                        },{
                            name: 'прибыль',
                            data: data.profit_data,
                            color: 'red',
                            lineWidth: 2,
                            tooltip: {
                                valueDecimals: 2
                            }
                        }
                    ]
                });

    }
