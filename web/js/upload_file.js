$(function () {
    $('#drag-and-drop-zone').dmUploader({
        url: "/site/upload-file",
        dataType: 'json',
        maxFileSize: 3145728,
        allowedTypes: 'html/*',
        fileName: 'file',
        onBeforeUpload: function (id) {
            updateFileStatus(id, 'default', 'Uploading...');
        },
        onNewFile: function (id, file) {

          addFile('#demo-files', id, file);

        },
        onComplete: function () {

        },
        onUploadProgress: function (id, percent) {
            var percentStr = percent + '%';

            updateFileProgress(id, percentStr);
        },
        onUploadSuccess: function (id, data) {
            updateFileStatus(id, 'success', 'Успешно загружено');

            updateFileProgress(id, '0%');
            if (data.status == 'ok') {
                var input = $('<input>').attr({
                    type: 'hidden',
                    name: 'loadFile'
                }).val(data.name);
                $("#demo-files").append(input.get(0));
            } else {
                alert(data.error);
            }
        },
        onUploadError: function (id, message) {
           alert("пожалуйста перегрузите страницу и попробуйте ещё раз");
        },
        onFileSizeError: function (file) {
           alert("файл с именем " + file.name + ' не должен превышать 3 Мб');
        },
        onFileTypeError: function (file) {
           alert("файл с именем " + file.name + 'должен быть изображением');
        }

    });


    function updateFileStatus(i, status, message) {
        $('#demo-file' + i).find('span.demo-file-status').html(message).addClass('demo-file-status-' + status);
    }

    function updateFileProgress(i, percent) {
        $('#demo-file' + i).find('div.progress-bar').width(percent);

        $('#demo-file' + i).find('span.sr-only').html(percent + ' Complete');
    }

    function addFile(id, i, file){

		var template = '<div id="demo-file' + i + '">' +
		                   '<span class="demo-file-id">#' + i + '</span> - ' + file.name + ' <span class="demo-file-size">(' + humanizeSize(file.size) + ')</span> - Status: <span class="demo-file-status">Waiting to upload</span>'+
		                   '<div class="progress progress-striped active">'+
		                       '<div class="progress-bar" role="progressbar" style="width: 0%;">'+
		                           '<span class="sr-only">0% Complete</span>'+
		                       '</div>'+
		                   '</div>'+
		               '</div>';

		var i = $(id).attr('file-counter');
		if (!i){
			$(id).empty();

			i = 0;
		}

		i++;

		$(id).attr('file-counter', i);

		$(id).prepend(template);

    }


    function humanizeSize(size) {
        var i = Math.floor(Math.log(size) / Math.log(1024));
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }
});