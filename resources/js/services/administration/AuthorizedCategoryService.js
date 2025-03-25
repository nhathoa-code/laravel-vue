import axios from "axios";
import Service from "../Service";

class AuthorizedCategoryServiceClass extends Service {
    base = "api/authorized-categories";

    async getValueList(callback = null, params = {}) {
        this.loading.value = true;
        const res = axios.get(this.base, { params: params });
        this.handleResponse(res, callback);
    }

    async insertValue(data, callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post(this.base, data.values, { params: params });
        this.handleResponse(res, callback, data?.actions);
    }

    async deleteValue(valueId, callback = null, params = {}) {
        this.deleting.value = true;
        const res = axios.delete(this.base + `/${valueId}`, { params: params });
        this.handleResponse(res, callback);
    }
}

export const AuthorizedCategoryService = new AuthorizedCategoryServiceClass();
