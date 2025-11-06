<template>
  <q-page padding>
    <div class="row q-col-gutter-md items-stretch">
      <div class="col-12 col-md-8">
        <q-card class="q-pa-md">
          <q-card-section>
            <div class="text-h5 q-mb-xs">
              Bem-vindo ao seu controle financeiro
            </div>
            <div class="text-subtitle2">
              Acompanhe aqui seus gastos mensais e os calculos
            </div>
          </q-card-section>
          <q-separator />
          <q-card-section>
            <q-btn
              color="primary"
              icon="add"
              label="Adicionar card"
              class="q-mr-sm"
              @click="dialog = true"
            />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card class="bg-primary text-white">
          <q-card-section>
            <div class="text-h6">Status</div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="text-h3 text-weight-bold q-mt-xl">Seus Cards</div>
    <div class="row wrap q-gutter-md items-center q-mt-xs">
      <q-card v-for="card in cards" class="col-12 col-md-3 shadow-4">
        <q-card-section>
            <div>{{ card.id }}</div>
            <div>{{ card.name }}</div>
            <div>{{ card.color }}</div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn label="Acessar" color="primary" />
          <q-btn outline color="negative" icon="delete" label="Deletar" />
        </q-card-actions>
      </q-card>
    </div>

    <q-dialog v-model="dialog">
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
          <q-input outlined label="Nome do card" v-model="cardName" />
          <q-input outlined label="Cor do card" v-model="cardColor">
            <template v-slot:append>
              <q-icon name="colorize" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-color v-model="cardColor" />
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </q-card-section>
        <q-separator />
        <q-card-actions class="q-pa-md" align="right">
          <q-btn color="primary" label="Adicionar" @click="addCard"/>
          <q-btn color="negative" outline label="Fechar" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { getCards, insertCard, type Cards } from "../api/cards";

const dialog = ref(false);
const cardName = ref<string>("");
const cardColor = ref<string>("");
const cards = ref<Cards[] | []>([]);

onMounted(async () => {
  await loadCards();
});

const loadCards = async () => {
  try {
    const data = await getCards();
    cards.value = Array.isArray(data)
      ? data
      : typeof data === "string" && (data as string).trim()
      ? JSON.parse(data)
      : [];
  } catch (e) {
    console.log(e);
  }
};

const addCard = async () => {
  const payload = {
    name: cardName.value,
    color: cardColor.value,
  };

  try {
    const data = await insertCard(payload);
    console.log("Deu certo", data);
  } catch (e) {
    console.log(e);
  }
};
</script>
