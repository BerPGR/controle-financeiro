<template>
  <q-page padding>
    <q-btn
      v-if="card"
      round
      size="lg"
      icon="chevron_left"
      :style="{
        backgroundColor: card.color,
        color: getTextColorFromDB(card.color),
      }"
      @click="replaceToHome"
    />
    <div
      v-if="card"
      class="row q-col-gutter-md items-stretch q-mt-md"
      :style="{ color: getTextColorFromDB(card.color) }"
    >
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md" :style="{ backgroundColor: card.color }">
          <q-item-section>
            <div class="text-h5 text-weight-bold">{{ card.name }}</div>
            <div>Nº de registros: {{ entries.length }}</div>
          </q-item-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md" :style="{ backgroundColor: card.color }">
          <q-item-section>
            <div class="text-h5 text-weight-bold">Rendimentos Totais</div>
            <div>{{ formattedValue(totalEarnings) }}</div>
          </q-item-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md" :style="{ backgroundColor: card.color }">
          <q-item-section>
            <div class="text-h5 text-weight-bold">Despesas Totais</div>
            <div>{{ formattedValue(totalExpenses) }}</div>
          </q-item-section>
        </q-card>
      </div>
    </div>

    <div v-else class="q-mt-md">
      <q-skeleton type="rect" height="120px" class="q-mb-md" />
      <q-skeleton type="rect" height="120px" class="q-mb-md" />
      <q-skeleton type="rect" height="120px" />
    </div>

    <q-btn
      v-if="card"
      icon="add"
      label="Novo registro"
      @click="dialog = true"
      :style="{
        backgroundColor: card.color,
        color: getTextColorFromDB(card.color),
      }"
      class="q-mt-xl"
    />

    <q-table
      class="q-mt-xl"
      bordered
      title="Registros"
      :rows="entries"
      :columns="columns"
      row-key="id"
      :pagination="initialPagination"
      no-data-label="Eu não achei nada para você"
      no-results-label="The filter didn't uncover any results"
      binary-state-sort
      selection="single"
      v-model:selected="selected"
    >
      <template v-slot:no-data="{ icon, message, filter }">
        <div class="full-width row flex-center q-gutter-sm">
          <q-icon size="2em" name="sentiment_dissatisfied" />
          <span>Bem... {{ message }} </span>
          <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
        </div>
      </template>
      <template v-slot:top-right>
        <q-btn
          icon="delete"
          color="negative"
          round
          flat
          @click="deleteEntry(selected, cardId)"
        />
      </template>
      <template v-slot:body-cell-amount="props">
        <q-td :props="props">
          <div>
            <q-badge @click="()  => console.log(props)"
              :color="props.row.kind === 'INVESTMENT' ? 'green' : 'red'"
              :label="props.value"
            />
          </div>
        </q-td>
      </template>
    </q-table>
    <QDialogCreateEntry
      :cardId="cardId"
      @addEntry="addNewEntry(cardId)"
      v-model:dialog="dialog"
      v-model="form"
    />
  </q-page>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { formattedValue, getTextColorFromDB } from "../util/utils";
import { useCardStore } from "../store/cardStore";
import type { Cards } from "../api/cards";
import type { QTableColumn } from "quasar";
import QDialogCreateEntry from "../components/Dialog/QDialogCreateEntry.vue";
import useEntries from "../composable/useEntries";

const route = useRoute();
const router = useRouter();
const { getCardDataFromStorage } = useCardStore();

const selected = ref([]);
const cardId = route.params.id as string;
const card = ref<Omit<Cards, "created_at"> | null>();
const { entries, dialog, form, loadEntries, addNewEntry, deleteEntry } = useEntries();

const initialPagination = {
  sortBy: "desc",
  descending: false,
  page: 1,
  rowsPerPage: 10,
};

const columns = ref<QTableColumn[]>([
  { name: "kind", label: "Tipo", align: "right", field: "kind" },
  {
    name: "description",
    label: "Descrição",
    align: "right",
    field: "description",
  },
  { name: "date", label: "Data", align: "right", field: "date" },
  {
    name: "amount",
    label: "Total",
    align: "right",
    field: "amount",
    format: (val: number) => formattedValue(val),
  },
]);

const totalExpenses = computed(() => {
  return entries.value
    .filter((e) => e.kind === "EXPENSE")
    .reduce((acc, item) => acc + Number(item.amount), 0);
});

const totalEarnings = computed(() => {
  return entries.value
    .filter((e) => e.kind === "INVESTMENT")
    .reduce((acc, item) => acc + Number(item.amount), 0);
});

onMounted(async () => {
  const stored = getCardDataFromStorage();

  if (stored) {
    card.value = stored;
  }
  await loadEntries(cardId as string);
});

const replaceToHome = () => {
  router.replace("/");
};
</script>
