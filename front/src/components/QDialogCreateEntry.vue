<template>
  <q-dialog v-model="dialogModel">
    <q-card style="min-width: 560px">
      <q-card-section>
        <div class="row items-center q-gutter-sm">
          <q-icon name="info" />
          <div class="text-h6">Novo card</div>
        </div>
        Adicione um novo card para controlar suas financias
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-md">
        <q-select
          outlined
          clearable
          label="Tipo"
          v-model="form.kind"
          :options="options"
        />
        <q-input
          outlined
          clearable
          label="Total"
          v-model="form.amount"
          type="number"
        />
        <q-input
          outlined
          clearable
          label="Descrição"
          v-model="form.description"
        />
        <q-input outlined v-model="form.date" mask="date" :rules="['date']" label="Data">
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="form.date">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-separator />
      <q-card-actions class="q-pa-md" align="right">
        <q-btn color="primary" label="Adicionar" @click="emits('addEntry', props.cardId)" />
        <q-btn color="negative" outline label="Fechar" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import type { CreateEntryKind } from '../types/types.ts'

const emits = defineEmits<{
  (e: "addEntry", id: string): void;
}>();

const dialogModel = defineModel<boolean>("dialog");

const form = defineModel<{
  kind: CreateEntryKind;
  description: string;
  amount: number;
  date: string;
}>({
  type: Object,
  required: true,
});

const props = defineProps<{
  cardId: string
}>()

const options: CreateEntryKind[] = [
  {
    label: "Despesa",
    value: "EXPENSE",
  },
  {
    label: "Rendimento",
    value: "INVESTMENT",
  },
];
</script>
