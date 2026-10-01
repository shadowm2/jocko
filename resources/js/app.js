import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

window.TomSelect = TomSelect;
function initDatePickers() {
    $(".persian-datepicker").each(function () {
        const input = this;
        const datePicker = $(input).pDatepicker({
            format: "YYYY/MM/DD",
            autoClose: true,
            initialValue: false,
            onSelect() {
                const value = $(input).val();
                const wireModel = $(input).attr("wire:model");

                Livewire.find(
                    input.closest("[wire\\:id]")?.getAttribute("wire:id"),
                ).$set(wireModel, value);
            },
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener("livewire:navigated", initDatePickers);
});
