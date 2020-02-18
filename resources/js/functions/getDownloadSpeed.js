export default () => {
    return new Promise((resolve, reject) => {
        var imageAddr =
            "https://nhmisc.s3.amazonaws.com/liswall/stub.jpg" +
            "?n=" +
            Math.random();

        var startTime, endTime;
        var downloadSize = 1025385;
        var download = new Image();

        download.onload = function() {
            endTime = new Date().getTime();
            calculate();
        };

        startTime = new Date().getTime();
        download.src = imageAddr;

        function calculate() {
            var duration = (endTime - startTime) / 1000;
            var bitsLoaded = downloadSize * 8;
            var speedBps = Math.round(bitsLoaded / duration);
            var speedKbps = (speedBps / 1024).toFixed(2);
            var speedMbps = (speedKbps / 1024).toFixed(2);
            resolve(+speedMbps);
        }
    });
};
