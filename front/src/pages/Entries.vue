<template>
  <q-page padding>
    <!-- ✅ Só renderiza a linha quando card existir -->
    <div v-if="card" class="row q-col-gutter-md items-stretch" :style="{ color: getTextColorFromDB(card.color) }">
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md" :style="{backgroundColor: card.color}">
          <q-item-section>
            <div class="text-h5 text-weight-bold"> {{ card.name }} </div>
          </q-item-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md" :style="{backgroundColor: card.color}">
          <q-item-section>
            <div class="text-h5 text-weight-bold">Rendimentos Totais</div>
          </q-item-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md" :style="{backgroundColor: card.color}">
          <q-item-section>
            <div class="text-h5 text-weight-bold">Despesas Totais</div>
          </q-item-section>
        </q-card>
      </div>
    </div>

    <!-- (Opcional) placeholder enquanto carrega -->
    <div v-else class="q-mt-md">
      <q-skeleton type="rect" height="120px" class="q-mb-md" />
      <q-skeleton type="rect" height="120px" class="q-mb-md" />
      <q-skeleton type="rect" height="120px" />
    </div>
    <!--<q-table
      flat
      bordered
      ref="tableRef"
      title="Treats"
      :rows="rows"
      :columns="columns"
      row-key="id"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      binary-state-sort
      @request="onRequest"
    >
      <template v-slot:top-right>
        <q-input
          borderless
          dense
          debounce="300"
          v-model="filter"
          placeholder="Search"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
</q-input>
</template>
</q-table>-->
  </q-page>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { getTextColorFromDB } from "../util/utils";
import { getEntriesFromCard, type Entries } from "../api/entries";
import { useCardStore } from "../store/cardStore";
import type { Cards } from "../api/cards";

const route = useRoute();
const { getCardDataFromStorage } = useCardStore()
const cardId = route.params.id;
const card = ref<Omit<Cards, 'created_at'> | null>()
const entries = ref<Entries[] | []>([]);

onMounted(async () => {
  const stored = getCardDataFromStorage()

  if (stored) {
    card.value = stored
  }
  await loadEntries(cardId as string);
});

const loadEntries = async (cardId: string) => {
  try {
    const data = await getEntriesFromCard(cardId);
    entries.value = Array.isArray(data)
      ? data
      : typeof data === "string" && (data as string).trim()
        ? JSON.parse(data)
        : [];
  } catch (e) {
    throw "Não foi possível buscar entradas para esse card";
  }
};
</script>