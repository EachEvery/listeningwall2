import random from "./randomString";

function getBucketObj() {
    AWS.config.region = window.aws.region; // 1. Enter your region

    AWS.config.credentials = new AWS.Credentials(aws.key, aws.secret);

    AWS.config.credentials.get(function(err) {
        if (err) alert(err);
    });

    return new AWS.S3({
        params: {
            Bucket: window.aws.bucket
        }
    });
}
export default (blob, ext = ".webm", prefix = "videos/") => {
    return new Promise((resolve, reject) => {
        let file = new File([blob], random(10) + ext);
        var objKey = prefix + file.name;
        var params = {
            Key: objKey,
            ContentType: file.type,
            Body: file,
            ACL: "public-read"
        };

        getBucketObj().putObject(params, function(err, data) {
            if (err) {
                reject(err);
            } else {
                resolve(
                    `https://s3.amazonaws.com/${window.aws.bucket}/${objKey}`
                );
            }
        });
    });
};
