import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

window.TomSelect = TomSelect;

function initSearchableSelects() {
    document.querySelectorAll("select[data-searchable-select]").forEach((select) => {
        if (select.tomselect) return;

        const tomSelect = new TomSelect(select, {
            plugins: select.multiple
                ? ["dropdown_input", "remove_button"]
                : ["dropdown_input"],
            create: false,
            closeAfterSelect: !select.multiple,
            hideSelected: select.multiple,
            placeholder: select.dataset.placeholder || "",
            onChange(value) {
                const component = select.closest("[wire\\:id]");
                const componentId = component?.getAttribute("wire:id");

                if (componentId && select.dataset.model) {
                    Livewire.find(componentId).$set(
                        select.dataset.model,
                        value,
                        false,
                    );
                }
            },
        });

        tomSelect.wrapper.classList.add("flux-searchable-select");
    });
}

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
    initSearchableSelects();
    window.addEventListener("livewire:navigated", initDatePickers);
    window.addEventListener("livewire:navigated", initSearchableSelects);
});
