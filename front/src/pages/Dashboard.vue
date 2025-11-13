<template>
  <q-page padding>
    <q-card class="col-12 col-md-6">
      <q-card-section>
        <div>Rendimentos X Despesas por Card</div>
      </q-card-section>
      <q-card-section v-if="loading">
        <q-spinner color="primary"/>
      </q-card-section>
      <q-card-section v-else>
        <highcharts :options="barChartOptions"></highcharts>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import useSummary from "../composable/useSummary";

const loading = ref<boolean>(false)

const { barChartOptions, loadSummary } = useSummary()

onMounted(async () => {
    try {
        loading.value = true
        await loadSummary()
    } catch (e) {
        console.log(e);
    } finally {
        loading.value = false
    }
})
</script>
