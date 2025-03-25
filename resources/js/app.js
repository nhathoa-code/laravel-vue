import "./bootstrap.js";
import "../css/app.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./components/admin/App.vue";
import PrimeVue from "primevue/config";
import ToastService from "primevue/toastservice";
import Aura from "@primevue/themes/aura";
import "primeicons/primeicons.css";

import router from "./router/admin/index.js";

const pinia = createPinia();
const app = createApp(App);

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            prefix: "p",
            darkModeSelector: ".fake-dark-selector",
            cssLayer: false,
        },
    },
});
app.use(ToastService);
app.use(pinia);
app.use(router);
app.mount("#app");
