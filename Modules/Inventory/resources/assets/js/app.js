import warehouseChannel from "@inventory/warehouse-channel.js";
import numericLine from "@inventory/numeric-line.js";

document.addEventListener("alpine:init", () => {
    Alpine.data("warehouseChannel", warehouseChannel);
    Alpine.data("numericLine", numericLine);
});
