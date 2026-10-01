let channelClosed = false;
let registered = false;
let channel = null;

function getChannel() {
    if (!channel || channelClosed) {
        channel = new BroadcastChannel("warehouse");
    }
    return channel;
}
export default function warehouseChannel() {
    return {
        init() {
            if (!registered) {
                registered = true;

                getChannel().onmessage = (event) => {
                    if (event.data?.type !== "active-warehouse-changed") {
                        return;
                    }

                    this.onWarehouseChanged(event.data.warehouse);
                };
            }
        },

        destroy() {
            getChannel()?.close();
            channelClosed = true;
        },

        broadcastWarehouseChange(warehouse) {
            getChannel().postMessage({
                type: "active-warehouse-changed",
                warehouse,
            });
        },

        onWarehouseChanged(warehouse) {
            Livewire.dispatch("active-warehouse-changed", {
                warehouse,
            });
        },
    };
}
