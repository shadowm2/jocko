import { isRtl, toPersianDigits } from "@dashboard/localized-digits.js";

// Numeric inputs emit their normalized value and field name, keeping this
// calculator independent of nested component refs.
export default function numericLine() {
    return {
        total: "0.00",
        quantity: 0,
        unitPrice: 0,

        parseNumber(value) {
            const persianDigits = "۰۱۲۳۴۵۶۷۸۹";
            const arabicDigits = "٠١٢٣٤٥٦٧٨٩";
            const normalized = String(value ?? "")
                .replace(/[۰-۹]/g, (digit) => persianDigits.indexOf(digit))
                .replace(/[٠-٩]/g, (digit) => arabicDigits.indexOf(digit))
                .replace(/[٫]/g, ".")
                .replace(/[٬]/g, "");

            return Number.parseFloat(normalized) || 0;
        },

        setValue(event) {
            if (event.detail.field === "quantity") this.quantity = event.detail.value;
            if (event.detail.field === "unit_price") this.unitPrice = event.detail.value;

            this.updateTotal();
        },

        updateTotal() {
            const quantity = this.parseNumber(this.quantity);
            const unitPrice = this.parseNumber(this.unitPrice);
            const total = (quantity * unitPrice).toFixed(2);

            this.total = isRtl(this.$el) ? toPersianDigits(total) : total;
        },
    };
}
