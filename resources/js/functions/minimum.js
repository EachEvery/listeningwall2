export default async (promise, minimum = 800) => {
    const resolutions = await Promise.all([
        promise,
        new Promise(resolve => {
            window.setTimeout(resolve, minimum, undefined);
        })
    ]);
    return Promise.resolve(resolutions.find(res => res !== undefined));
};
