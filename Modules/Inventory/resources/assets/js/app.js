import warehouseChannel from "@inventory/warehouse-channel.js";

document.addEventListener("alpine:init", () => {
    Alpine.data("warehouseChannel", warehouseChannel);
});
