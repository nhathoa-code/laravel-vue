<script setup>
import { onMounted, ref } from "vue";
import { RouterLink } from "vue-router";
import { CashRegisterService } from "@root/services/pos/CashRegisterService";
import Loading from "@admin/common/Loading.vue";
const cashRegisters = ref([]);
onMounted(() => {
    CashRegisterService.getList((res) => {
        console.log(res);
        cashRegisters.value = res.data;
    });
});
</script>
<template>
    <Loading :visible="CashRegisterService.loading.value" />
    <h3 class="mb-3">Cash summary</h3>
    <div class="row">
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Register name</th>
                                    <th>Register description</th>
                                    <th>Library</th>
                                    <th>Last cashup</th>
                                    <th>Float</th>
                                    <th>Bankable</th>
                                    <th>Income (cash)</th>
                                    <th>Outgoing (cash)</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in cashRegisters"
                                    :key="item.id"
                                >
                                    <td>
                                        <RouterLink
                                            :to="`register?registerId=${item.id}`"
                                        >
                                            {{ item.name }}
                                        </RouterLink>
                                    </td>
                                    <td>{{ item.description }}</td>
                                    <td>{{ item.to_library.name }}</td>
                                    <td>
                                        {{
                                            item.last_cashup ?? "No last cashup"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(item.initial_amount) + "đ"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(item.total_bankable) + "đ"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(item.total_income) + "đ"
                                        }}
                                        ({{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(item.total_income_cash) +
                                            "đ"
                                        }})
                                    </td>
                                    <td>
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(item.total_outgoing) +
                                            " đ"
                                        }}
                                        (
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(
                                                item.total_outgoing_cash
                                            ) + " đ"
                                        }})
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            class="cashup_individual btn btn-xs btn-default action"
                                        >
                                            <i class="fa fa-money"></i>
                                            Record cashup
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
