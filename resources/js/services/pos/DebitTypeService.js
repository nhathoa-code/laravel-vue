import axios from "axios";
import Service from "@root/services/Service";

class DebitTypeServiceClass extends Service {
    base = "api/debit-types";

    async getList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }
}

export const DebitTypeService = new DebitTypeServiceClass();
