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
            <div class="text-h6 text-weight-bold">Status</div>
            <div class="text-body2">Nº de Cards: {{ cards.length }}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="text-h3 text-weight-bold q-mt-xl">Seus Cards</div>
    <div class="row wrap q-gutter-md items-center q-mt-xs">
      <q-card
        v-for="card in cards"
        class="col-12 col-md-3 shadow-4"
        :style="{
          backgroundColor: card.color,
          color: getTextColorFromDB(card.color),
        }"
      >
        <q-card-section>
          <div class="row items-center justify-between">
            <div class="text-h5 text-weight-bold">{{ card.name }}</div>
            <q-btn flat round icon="menu">
              <q-menu
                :offset="[120, 0]"
                transition-show="jump-down"
                transition-hide="jump-up"
              >
                <q-list>
                  <q-item clickable v-ripple v-close-popup>
                    <q-item-section>Editar</q-item-section>
                    <q-item-section avatar>
                      <q-icon name="edit" />
                    </q-item-section>
                  </q-item>
                  <q-item clickable v-ripple v-close-popup>
                    <q-item-section>Apagar card</q-item-section>
                    <q-item-section avatar>
                      <q-icon name="delete" />
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </div>
          <div class="text-body1">
            Criado em {{ formatDate(card.created_at) }}
          </div>
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn
            :to="`/entries/${card.id}`"
            flat
            label="Acessar"
            :style="{ color: getTextColorFromDB(card.color), border: `1px solid ${getTextColorFromDB(card.color)}`, borderRadius: '5px' }"
          />
        </q-card-actions>
      </q-card>
    </div>

    <QDialogCreateCard @addCard="addCard" v-model="cardForm" :dialog="dialog" />
  </q-page>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { getCards, insertCard, type Cards } from "../api/cards";
import QDialogCreateCard from "../components/QDialogCreateCard.vue";
import { getTextColor, rgbGetTextColor } from "../util/utils";

interface CardForm {
  cardName: string;
  cardColor: string;
}

const dialog = ref(false);
const cardForm = ref<CardForm>({
  cardName: "",
  cardColor: "",
});

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
  try {
    const data = await insertCard({
      name: cardForm.value.cardName,
      color: cardForm.value.cardColor,
    });
    dialog.value = false;
    await loadCards();
  } catch (e) {
    console.log(e);
  }
};

const dateFormatter = Intl.DateTimeFormat("pt-BR", {
  day: "2-digit",
  month: "2-digit",
  year: "numeric",
});

const formatDate = (date: Date | string) => {
  return dateFormatter.format(new Date(date));
};

const getTextColorFromDB = (textColor: string) => {
  if (textColor.startsWith('rgb')) {
    return rgbGetTextColor(textColor)
  } else if (textColor.startsWith("#")){
    return getTextColor(textColor)
  }
}
</script>
