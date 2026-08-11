import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

window.TomSelect = TomSelect;

function initDatePickers() {
    $(".persian-datepicker").each(function () {
        console.log("initializing");
        const input = this;
        $(input).pDatepicker({
            format: "YYYY/MM/DD",
            autoClose: true,
            onShow() {},
            onSelect() {
                const value = $(input).val();

                Livewire.find(
                    input.closest("[wire\\:id]").getAttribute("wire:id"),
                ).$set("form.manufactured_at", value);
            },
        });
    });
}

$(initDatePickers);
