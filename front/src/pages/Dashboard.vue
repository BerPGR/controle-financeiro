<template>
  <q-page padding>
    <div class="text-h3 text-weight-bold">Seu relatório</div>
    <div class="text-body1 q-mt-xs">
      Este aqui é o seu relatório mensal. <br />Você pode alterar os valores
      conforme você deseja, apenas aplicandos os filtros que quiser abaixo
    </div>

    <div class="row wrap q-gutter-md q-mt-lg">
      <q-card class="col-6">
        <q-card-section v-if="loading">
          <q-spinner color="primary" />
        </q-card-section>
        <q-card-section v-else>
          <highcharts :options="barChartOptions"></highcharts>
        </q-card-section>
      </q-card>

      <q-card class="col-5">
        <q-card-section v-if="loading">
          <q-spinner color="primary" />
        </q-card-section>
        <q-card-section v-else>
          <highcharts :options="pieChartTotalOptions"></highcharts>
        </q-card-section>
      </q-card>

      <q-card class="col-3">
        <q-card-section v-if="loading">
          <q-spinner color="primary" />
        </q-card-section>
        <q-card-section v-else>
          <highcharts :options="pieChartTotalExpenseCardsOptions"></highcharts>
        </q-card-section>
      </q-card>

      <q-card class="col-8">
        <q-card-section v-if="loading">
          <q-spinner color="primary" />
        </q-card-section>
        <q-card-section v-else>
          <highcharts :options="lineMonthlyExpenseChart"></highcharts>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import useSummary from "../composable/useSummary";

const loading = ref<boolean>(false);

const {
  pieChartTotalExpenseCardsOptions,
  pieChartTotalOptions,
  barChartOptions,
  lineMonthlyExpenseChart,
  loadSummary,
} = useSummary();

onMounted(async () => {
  try {
    loading.value = true;
    await loadSummary();
  } catch (e) {
    console.log(e);
  } finally {
    loading.value = false;
  }
});
</script>
