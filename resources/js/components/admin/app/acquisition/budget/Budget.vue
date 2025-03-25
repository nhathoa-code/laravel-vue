<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { BudgetService } from "@root/services/acquisition/BudgetService";
import { formatCurrency, getTotal } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
const route = useRoute();
const router = useRouter();
const budget = ref(null);
onMounted(() => {
    BudgetService.getById(route.params.id, (res) => {
        console.log(res);
        budget.value = res.data;
    });
});
function deleteFund(fund) {
    if (
        !confirm(
            `Confirm deletion of fund '${fund.name}'? If you delete this fund, all orders linked to this fund will be deleted!`
        )
    ) {
        return;
    }
    BudgetService.deleteFund(budget.value.id, fund.id, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <Loading
        :visible="BudgetService.loading.value || BudgetService.deleting.value"
    />
    <template v-if="budget">
        <div id="toolbar" class="btn-toolbar">
            <RouterLink :to="`/admin/acquisition/budget`">
                <button class="btn btn-default">
                    <i class="pi pi-plus"></i> New budget
                </button>
            </RouterLink>
            <RouterLink :to="`/admin/acquisition/budget/${budget.id}/addfund`">
                <button class="btn btn-default">
                    <i class="pi pi-plus"></i> New fund for {{ budget.name }}
                </button>
            </RouterLink>
            <RouterLink :to="`/admin/acquisition/budget/${budget.id}/edit`">
                <button class="btn btn-default">
                    <i class="pi pi-pencil"></i> Edit
                </button>
            </RouterLink>
        </div>
        <h3 class="title">Funds for budget '{{ budget.name }}'</h3>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Fund code"
                                        >
                                            Fund code
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Fund name"
                                        >
                                            Fund name
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Base-level allocated"
                                        >
                                            Base-level allocated
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Base-level ordered"
                                        >
                                            Base-level ordered
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Total ordered"
                                        >
                                            Total ordered
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Base-level spent"
                                        >
                                            Base-level spent
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Total spent"
                                        >
                                            Total spent
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Base-level available"
                                        >
                                            Base-level available
                                        </th>
                                        <th
                                            class="sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Total available"
                                        >
                                            Total available
                                        </th>
                                        <th
                                            class="noExport sorting_disabled"
                                            rowspan="1"
                                            colspan="1"
                                            aria-label="Actions"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="10" class="group">
                                            Budget {{ budget.name }} [id={{
                                                budget.id
                                            }}]
                                        </td>
                                    </tr>
                                    <tr v-for="item in budget.funds">
                                        <td>
                                            {{ item.code }}
                                        </td>
                                        <td>{{ item.name }}</td>
                                        <td class="data">
                                            <span
                                                class="total_amount"
                                                data-parent_id=""
                                                data-self_id="6"
                                                >{{
                                                    formatCurrency(item.amount)
                                                }}</span
                                            >
                                        </td>
                                        <td class="data">
                                            {{
                                                formatCurrency(item.totalOrders)
                                            }}
                                        </td>
                                        <td class="data">
                                            <span class="total_amount">
                                                {{
                                                    formatCurrency(
                                                        item.totalOrders
                                                    )
                                                }}</span
                                            >
                                        </td>
                                        <td class="data">
                                            <span class="total_amount">
                                                {{
                                                    formatCurrency(
                                                        item.totalSpent
                                                    )
                                                }}</span
                                            >
                                        </td>
                                        <td class="data">
                                            <span class="total_amount">{{
                                                formatCurrency(item.totalSpent)
                                            }}</span>
                                        </td>
                                        <td class="data">
                                            <span
                                                :style="
                                                    item.availableAmount < 0
                                                        ? 'color: red'
                                                        : 'color: green'
                                                "
                                            >
                                                {{
                                                    formatCurrency(
                                                        item.availableAmount
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td class="data">
                                            <span
                                                :style="
                                                    item.availableAmount < 0
                                                        ? 'color: red'
                                                        : 'color: green'
                                                "
                                            >
                                                {{
                                                    formatCurrency(
                                                        item.availableAmount
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td>
                                            <RouterLink
                                                :to="{
                                                    name: 'fund-edit',
                                                    params: {
                                                        id: budget.id,
                                                        fundId: item.id,
                                                    },
                                                }"
                                            >
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-light btn-fw action"
                                                >
                                                    <i class="pi pi-pencil"></i>
                                                    Edit
                                                </button></RouterLink
                                            >
                                            <button
                                                @click="deleteFund(item)"
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="pi pi-trash"></i>
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th
                                            colspan="2"
                                            style="text-align: left"
                                            nowrap="nowrap"
                                            rowspan="1"
                                        >
                                            Period allocated
                                            {{
                                                formatCurrency(
                                                    budget.total_amount
                                                )
                                            }}
                                        </th>
                                        <th
                                            nowrap="nowrap"
                                            class="data"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                getTotal(budget.funds, "amount")
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                        <th
                                            class="data"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                getTotal(
                                                    budget.funds,
                                                    "totalOrders"
                                                )
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                        <th
                                            class="data"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                getTotal(
                                                    budget.funds,
                                                    "totalSpent"
                                                )
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                        <th rowspan="1" colspan="1">
                                            {{
                                                getTotal(
                                                    budget.funds,
                                                    "availableAmount"
                                                )
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
