$(function () {
    $('#drag-and-drop-zone').dmUploader({
        url: "/site/upload-file",
        dataType: 'json',
        maxFileSize: 3145728,
        allowedTypes: 'html/*',
        fileName: 'file',
//        onBeforeUpload: function (id) {
//            updateFileStatus(id, 'default', 'Загрузка...');
//        },

//        onNewFile: function (id, file) {
//            addFile('#demo-files', id, file);
//        },

//        onComplete: function () {
//
//        },

        onUploadProgress: function (id, percent) {
            var percentStr = percent + '%';
        },

        onUploadSuccess: function (id, result) {
            if (!result.hasErrors) {
                addReport(result);
                bsAlert.success('Файл успешно загружен');
                drawChartByData(result.full_info);
            } else {
                bsAlert.error(result.message);
            }
        },
        onUploadError: function (id, message) {
            bsAlert.error("Пожалуйста перегрузите страницу и попробуйте ещё раз");
        },
        onFileSizeError: function (file) {
            bsAlert.error("Файл не должен превышать 3 Мб");
        },
        onFileTypeError: function (file) {
            bsAlert.error("Недопустимый формат файла. Должен иметь расширение html");
        }

    });

    function addReport(data) {
        $('#list-report-empty').css('display', 'none');
        var html = "<div class='report' onclick='drawChartByNameFile(\"" + data.file_name + "\")'>" + data.full_info.name + "</div>";
        $('#container-reports').append(html);
    }


    function humanizeSize(size) {
        var i = Math.floor(Math.log(size) / Math.log(1024));
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }
});