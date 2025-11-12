<template>
  <q-layout view="hHh lpR fFf">
    <q-header reveal elevated class="bg-primary text-white">
      <q-toolbar class="q-pa-md">
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>
          <q-avatar>
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
          </q-avatar>
          Controle Financeiro
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      behavior="desktop"
      bordered
    >
      <q-list>
        <template v-for="(item, i) in menuList" :key="i">
          <q-item clickable v-ripple :to="item.to">
            <q-item-section avatar>
              <q-icon :name="item.icon"/>
            </q-item-section>
            <q-item-section>
              {{ item.label }}
            </q-item-section>
          </q-item>
          <q-separator :key="'sep' + i" v-if="item.separator"/>
        </template>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from "vue";

const leftDrawerOpen = ref<boolean>(false);

const menuList = [
  {
    icon: "home",
    label: "Home",
    to: '/',
    separator: false,
  },
  {
    icon: "dashboard",
    label: "Relatório",
    to: '/dashboard',
    separator: true,
  },
  {
    icon: "settings",
    label: "Configurações",
    to: '/settings',
    separator: false,
  },
];

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>
