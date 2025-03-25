import axios from "axios";
import Service from "../Service";

class StaffServiceClass extends Service {
    base = "api/staff";

    async login(data, callback = null) {
        this.submitting.value = true;
        const res = axios.post("login", data.values);
        this.handleResponse(res, callback, data?.actions);
    }

    async logout(callback = null, params = {}) {
        this.submitting.value = true;
        const res = axios.post("logout", { params: params });
        this.handleResponse(res, callback);
    }
}

export const StaffService = new StaffServiceClass();
