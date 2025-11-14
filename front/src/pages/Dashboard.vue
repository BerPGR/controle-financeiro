<template>
  <q-page padding>
    <div class="text-h3 text-weight-bold">Seu relatório</div>
    <div class="text-body1 q-mt-xs">
      Este aqui é o seu relatório mensal. <br />Você pode alterar os valores
      conforme você deseja, apenas aplicandos os filtros que quiser abaixo (Atividade futura, hehe)
    </div>

    <div class="row wrap q-gutter-md q-mt-lg">
      <q-card class="col-6">
        <q-card-section>
          <div class="text-h6 text-weight-bold">Rendimentos x Despesas por Card</div>
        </q-card-section>
        <q-card-section>
          <highcharts v-if="barChartOptions.series" :options="barChartOptions"></highcharts>
        </q-card-section>
      </q-card>

      <q-card class="col-5">
        <q-card-section>
          <div class="text-h6 text-weight-bold">Rendimento x Despesa Total</div>
        </q-card-section>
        <q-card-section>
          <highcharts :options="pieChartTotalOptions"></highcharts>
        </q-card-section>
      </q-card>

      <q-card class="col-4">
        <q-card-section>
          <div class="text-h6 text-weight-bold">Despesa Total por Card</div>
        </q-card-section>
        <q-card-section>
          <highcharts :options="pieChartTotalExpenseCardsOptions"></highcharts>
        </q-card-section>
      </q-card>

      <q-card class="col-7">
        <q-card-section>
          <div class="text-h6 text-weight-bold">Despesa Mensal por Card</div>
        </q-card-section>
        <q-card-section>
          <highcharts v-if="lineMonthlyExpenseChart.series!.length > 0" :options="lineMonthlyExpenseChart"></highcharts>
          <div v-else class="text-h4 text-center">Sem data para expor</div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import useSummary from "../composable/useSummary";

const {
  pieChartTotalExpenseCardsOptions,
  pieChartTotalOptions,
  barChartOptions,
  lineMonthlyExpenseChart,
  loadSummary,
} = useSummary();

onMounted(async () => {
  try {
    await loadSummary();
  } catch (e) {
    console.log(e);
  }
});
</script>
