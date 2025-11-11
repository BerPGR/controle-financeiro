import { defineStore } from "pinia";
import {ref} from 'vue'
import type { Cards } from "../api/cards";

export const useCardStore = defineStore('cards', () => {
    const cardData = ref<Omit<Cards, 'created_at'>>({
        id: '',
        name: '',
        color: ''
    })

    function setCardData(id: string, name: string, color: string) {
        const payload = { id, name, color };
        cardData.value = payload;
        localStorage.setItem("card", JSON.stringify(payload));
    }

    function getCardDataFromStorage() {
        try {
            const raw = localStorage.getItem("card");
            if (!raw) return null;
            
            const data = JSON.parse(raw);
            if (!data || typeof data !== "object") return null;

            if (!("id" in data) || !("name" in data) || !("color" in data)) return null;
      
            cardData.value = { id: data.id, name: data.name, color: data.color };
            return cardData.value;
          } catch (e) {
            console.warn("Valor inválido em localStorage['card']", e);
            return null;
          }
    }

    function clearCardData() {
        cardData.value = {
            id: '',
            name: '',
            color: ''
        }

        localStorage.setItem('card', '')
    }

    return { cardData, setCardData, getCardDataFromStorage, clearCardData }
})