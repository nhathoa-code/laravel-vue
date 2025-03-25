import axios from "axios";
import Service from "@root/services/Service";

class PosServiceClass extends Service {
    base = "api/pos";

    async transaction(data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base + "/transaction", data.values, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async cashup(registerId, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base + `/${registerId}/cashup`, {
            params: params,
        });
        this.handleResponse(res, callback);
    }

    async refund(refundedItem, data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(
            this.base + `/refund/${refundedItem}`,
            data.values,
            {
                params: params,
            }
        );
        this.handleResponse(res, callback, data?.actions);
    }
}

export const PosService = new PosServiceClass();
