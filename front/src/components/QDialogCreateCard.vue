<template>
  <q-dialog v-model="props.dialog">
    <q-card style="min-width: 480px">
      <q-card-section>
        <div class="row items-center q-gutter-sm">
          <q-icon name="info" />
          <div class="text-h6">Novo card</div>
        </div>
        Adicione um novo card para controlar suas financias
      </q-card-section>
      <q-separator />
      <q-card-section class="q-gutter-md">
        <q-input outlined label="Nome do card" v-model="form.cardName" />
        <q-input outlined label="Cor do card" v-model="form.cardColor">
          <template v-slot:append>
            <q-icon name="colorize" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-color v-model="form.cardColor" />
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>
      </q-card-section>
      <q-separator />
      <q-card-actions class="q-pa-md" align="right">
        <q-btn color="primary" label="Adicionar" @click="emit('addCard')" />
        <q-btn color="negative" outline label="Fechar" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
const form = defineModel<{
    cardName: string,
    cardColor: string
}>({
    type: Object,
    required: true
})

const emit = defineEmits<{
    (e: 'addCard'): void
}>()

const props = defineProps<{
    dialog: boolean
}>()
</script>