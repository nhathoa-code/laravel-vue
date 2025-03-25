import { defineStore } from "pinia";
export const useDataStore = defineStore("data", {
    state: () => ({ libraries: [] }),
    // getters: {
    //     getUser: (state) => state.user,
    // },
    actions: {
        setLibraries(libraries) {
            this.libraries = libraries;
        },
    },
});
