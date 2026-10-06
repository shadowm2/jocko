import localizedDigits, { toPersianDigits } from "@dashboard/localized-digits.js";

document.addEventListener("alpine:init", () => {
    Alpine.data("localizedDigits", localizedDigits);
    Alpine.magic("persianDigits", () => toPersianDigits);
});
