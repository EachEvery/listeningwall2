export default function(units, inverse = false) {
    let multiplier =
        parseInt(
            window
                .getComputedStyle(document.body, null)
                .getPropertyValue("font-size")
        ) / 7;

    if (inverse) {
        return units / multiplier;
    }
    return units * multiplier;
}
