export default () => {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest(),
            url =
                "/upload-speed-stub?cache=" + Math.floor(Math.random() * 10000), //prevent url cache
            data = getRandomString(5), //1 meg POST size handled by all servers
            startTime;

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                resolve(
                    Math.round(1024 / ((new Date() - startTime) / 1000)) / 100
                );
            }
        };

        xhr.setRequestHeader("X-CSRF-TOKEN", window.Laravel.csrf);

        xhr.open("POST", url, true);
        startTime = new Date();
        xhr.send(data);
    });
};
function getRandomString(sizeInMb) {
    var chars =
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789~!@#$%^&*()_+`-=[]{}|;':,./<>?", //random data prevents gzip effect
        iterations = sizeInMb * 1024 * 1024, //get byte count
        result = "";
    for (var index = 0; index < iterations; index++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}
