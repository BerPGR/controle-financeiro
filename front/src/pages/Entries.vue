<template>
  <q-page padding>
    <div class="row q-col-gutter-md items-stretch">
      <div class="col-12 col-md-4">
        <q-card class="q-pa-md bg-blue-12">
          <q-item-section>
            <div class="text-h5 text-weight-bold">Card aqui</div>
          </q-item-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card>
          <q-item-section>
            <div class="text-h5 text-weight-bold bg-blue-12">Card aqui</div>
          </q-item-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="col-12 col-md-4">
          <q-item-section>
            <div class="text-h5 text-weight-bold bg-blue-12">Card aqui</div>
          </q-item-section>
        </q-card>
      </div>
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
import { getEntriesFromCard, type Entries } from "../api/entries";

const route = useRoute();

const cardId = route.params.id;
const entries = ref<Entries[] | []>([]);

onMounted(async () => {
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
