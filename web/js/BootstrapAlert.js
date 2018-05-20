BootstrapAlert = function () {
    this.error = function (message) {
        $("#alert_placeholder").empty()
            .html('<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><span>' + message + '</span></div>');
        setTimeout(function () {

            $("#alert_placeholder").empty();

        }, 50000);

    };

    this.success = function (message) {
        $("#alert_placeholder").empty()
            .html('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><span>' + message + '</span></div>');

        setTimeout(function () {

            $("#alert_placeholder").empty();

        }, 5000);

    };

    this.info = function (message) {
        $("#alert_placeholder").empty()
            .html('<div class="alert alert-info fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><span>' + message + '</span></div>');

        setTimeout(function () {

            $("#alert_placeholder").empty();

        }, 5000);

    };

    this.warning = function (message) {
        var date = +new Date();
        $("#alert_placeholder").empty()
            .html('<div class="alert alert-warning fade in" id="' + date + '"><a href="#" class="close" data-dismiss="alert">&times;</a><span>' + message + '</span></div>');

        setTimeout(function () {

            $("#alert_placeholder").empty();

        }, 5000);

    };
};

var bsAlert = new BootstrapAlert();
