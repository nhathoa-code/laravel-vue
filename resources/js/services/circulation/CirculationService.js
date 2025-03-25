import axios from "axios";
import Service from "@root/services/Service";

class CirculationServiceClass extends Service {
    base = "api/circulations";

    async checkout(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base + "/checkout", data.values);
        this.handleResponse(res, callback);
    }

    async checkin(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base + "/checkin", data.values);
        this.handleResponse(res, callback);
    }

    async renew(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post(this.base + "/renew", data.values);
        this.handleResponse(res, callback);
    }

    async getOverdues(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base + "/overdues", { params: { params } });
        this.handleResponse(res, callback);
    }
}

export const CirculationService = new CirculationServiceClass();
