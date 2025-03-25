import axios from "axios";
import Service from "@root/services/Service";

class FundServiceClass extends Service {
    base = "api/funds";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }
}

export const FundService = new FundServiceClass();
