export function isRtl(element) {
    const root = document.documentElement;

    return root.dir === "rtl"
        || window.getComputedStyle(element).direction === "rtl"
        || root.lang.toLowerCase().startsWith("fa");
}

export function toPersianDigits(value) {
    const persianDigits = "۰۱۲۳۴۵۶۷۸۹";

    return String(value).replace(/[0-9]/g, (digit) => persianDigits[Number(digit)]);
}

export function toEnglishDigits(value) {
    const persianDigits = "۰۱۲۳۴۵۶۷۸۹";
    const arabicDigits = "٠١٢٣٤٥٦٧٨٩";

    return String(value)
        .replace(/[۰-۹]/g, (digit) => persianDigits.indexOf(digit))
        .replace(/[٠-٩]/g, (digit) => arabicDigits.indexOf(digit))
        .replace(/[٫]/g, ".");
}

export function restrictToDigits(input, persian) {
    const original = input.value;
    const cursor = input.selectionStart;
    const prefix = original.slice(0, cursor ?? original.length);
    const maxIntegerDigits = Number(input.dataset.maxIntegerDigits ?? 14);
    const maxDecimalPlaces = Number(input.dataset.maxDecimalPlaces ?? 4);
    const sanitize = (value) => {
        let decimalSeen = false;
        let integerDigits = 0;
        let fractionalDigits = 0;

        return [...value].filter((character) => {
            if (/[0-9۰-۹٠-٩]/.test(character)) {
                if (decimalSeen) {
                    if (fractionalDigits >= maxDecimalPlaces) return false;
                    fractionalDigits++;
                } else {
                    if (integerDigits >= maxIntegerDigits) return false;
                    integerDigits++;
                }

                return true;
            }
            if (/[.٫]/.test(character) && !decimalSeen && maxDecimalPlaces > 0) {
                decimalSeen = true;
                return true;
            }

            return false;
        }).join("");
    };
    const sanitized = sanitize(original);
    const englishValue = toEnglishDigits(sanitized);
    const displayValue = persian ? toPersianDigits(englishValue) : englishValue;

    if (displayValue === original) return;
    if (input.dataset.localizedDigitsSync === "true") return;

    const nextCursor = sanitize(prefix).length;

    input.dataset.localizedDigitsSync = "true";
    input.value = englishValue;

    if (typeof input.setSelectionRange === "function") {
        input.setSelectionRange(nextCursor, nextCursor);
    }

    // Sync the English value into Livewire, then restore the localized display.
    input.dispatchEvent(new Event("input", { bubbles: true }));
    queueMicrotask(() => {
        input.value = displayValue;

        if (typeof input.setSelectionRange === "function") {
            input.setSelectionRange(nextCursor, nextCursor);
        }

        delete input.dataset.localizedDigitsSync;
    });
}

// Usage: <x-dashboard::numeric-input wire:model.number="..." />
// Or use x-data="localizedDigits" and @input="sanitize($event)" on a text input.
export default function localizedDigits() {
    return {
        init() {
            const rtl = isRtl(this.$el);
            this.$el.dir = "ltr";

            if (rtl) {
                this.$el.value = toPersianDigits(this.$el.value);
            }

            this.$nextTick(() => this.notifyValue(this.$el));
        },

        sanitize(event) {
            if (event.target.dataset.localizedDigitsSync === "true") return;

            const rtl = isRtl(this.$el);
            this.$el.dir = "ltr";
            restrictToDigits(event.target, rtl);
            this.notifyValue(event.target);
        },

        notifyValue(input) {
            this.$dispatch("localized-digits-input", {
                field: input.dataset.numericField,
                value: toEnglishDigits(input.value),
            });
        },
    };
}
