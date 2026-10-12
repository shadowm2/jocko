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
                let wireModel, isLive;

                if ($(input).attr("wire:model.live") === undefined) {
                    wireModel = $(input).attr("wire:model");
                    isLive = false;
                } else {
                    wireModel = $(input).attr("wire:model.live");
                    isLive = true;
                }

                Livewire.find(
                    input.closest("[wire\\:id]")?.getAttribute("wire:id"),
                ).$set(wireModel, value, isLive);
            },
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener("livewire:navigated", initDatePickers);
});
