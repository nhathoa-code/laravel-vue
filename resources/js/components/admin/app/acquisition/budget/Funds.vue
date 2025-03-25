<script setup>
import { onMounted, ref } from "vue";
import { formatCurrency } from "@root/functions";
import { FundService } from "@root/services/acquisition/FundService";
import Loading from "@admin/common/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
const funds = ref(null);
onMounted(() => {
    FundService.getList((res) => {
        funds.value = res.data;
    });
});
</script>
<template>
    <Loading :visible="FundService.loading.value" />
    <template v-if="funds">
        <DataTable
            :value="funds"
            paginator
            :rows="5"
            :rowsPerPageOptions="[5, 10, 20, 50]"
        >
            <Column field="code" header="Code"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="amount" header="Base-level allocated">
                <template #body="{ data }">
                    {{ formatCurrency(data.amount) }}
                </template>
            </Column>
            <Column header="Actions">
                <template #body="{ data }">
                    <button
                        type="button"
                        class="btn btn-outline-light btn-fw action"
                    >
                        <i class="pi pi-pencil"></i>
                        Edit
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-light btn-fw action"
                    >
                        <i class="pi pi-trash"></i>
                        Delete
                    </button>
                </template>
            </Column>
        </DataTable>
    </template>
</template>
