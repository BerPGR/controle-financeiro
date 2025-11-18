<template>
  <q-page class="auth-page column flex-center">
    <q-card class="auth-card">
      <q-card-section>
        <div class="text-h5 text-weight-medium text-center">
          Controle Financeiro
        </div>
        <div class="text-subtitle2 text-center text-grey-7">
          Acesse sua conta ou crie um cadastro gratuito
        </div>
      </q-card-section>

      <q-separator />

      <q-tabs
        v-model="tab"
        dense
        class="text-primary"
        align="justify"
        active-color="primary"
        indicator-color="primary"
      >
        <q-tab name="login" label="Entrar" icon="login" />
        <q-tab name="register" label="Criar conta" icon="person_add" />
      </q-tabs>

      <q-separator />

      <q-tab-panels v-model="tab" animated class="bg-transparent">
        <q-tab-panel name="login">
          <q-form @submit.prevent="handleLogin" class="q-gutter-md">
            <q-input
              v-model="loginForm.email"
              type="email"
              label="Email"
              filled
              :rules="[(val) => !!val || 'Informe seu email']"
              autocomplete="email"
            />
            <q-input
              v-model="loginForm.password"
              type="password"
              label="Senha"
              filled
              :rules="[(val) => !!val || 'Informe sua senha']"
              autocomplete="current-password"
            />
            <q-btn
              label="Entrar"
              color="primary"
              rounded
              type="submit"
              :loading="loading"
            />
          </q-form>
        </q-tab-panel>

        <q-tab-panel name="register">
          <q-form @submit.prevent="handleRegister" class="q-gutter-md">
            <q-input
              v-model="registerForm.name"
              label="Nome"
              filled
              :rules="[(val) => !!val || 'Informe seu nome']"
              autocomplete="name"
            />
            <q-input
              v-model="registerForm.email"
              type="email"
              label="Email"
              filled
              :rules="[(val) => !!val || 'Informe seu email']"
              autocomplete="email"
            />
            <q-input
              v-model="registerForm.password"
              type="password"
              label="Senha"
              filled
              :rules="[
                (val) => !!val || 'Informe uma senha',
                (val) => val.length >= 6 || 'Mínimo de 6 caracteres',
              ]"
              autocomplete="new-password"
            />
            <q-input
              v-model="registerForm.confirmPassword"
              type="password"
              label="Confirmar senha"
              filled
              :rules="[(val) => val === registerForm.password || 'As senhas não conferem']"
              autocomplete="new-password"
            />
            <q-btn
              label="Criar conta"
              color="primary"
              rounded
              type="submit"
              :loading="loading"
            />
          </q-form>
        </q-tab-panel>
      </q-tab-panels>
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { useAuthStore } from '../store/useAuth'

const tab = ref<'login' | 'register'>('login')
const loading = ref(false)
const router = useRouter()
const $q = useQuasar()
const authStore = useAuthStore()

const loginForm = reactive({
  email: '',
  password: '',
})

const registerForm = reactive({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
})

const notifyError = (message: string) => {
  $q.notify({ type: 'negative', message })
}

const notifySuccess = (message: string) => {
  $q.notify({ type: 'positive', message })
}

const resetForms = () => {
  loginForm.email = ''
  loginForm.password = ''
  registerForm.name = ''
  registerForm.email = ''
  registerForm.password = ''
  registerForm.confirmPassword = ''
}

const redirectToHome = () => {
  router.push('/')
}

const handleLogin = async () => {
  try {
    loading.value = true
    await authStore.login(loginForm.email, loginForm.password)
    notifySuccess('Login realizado com sucesso!')
    redirectToHome()
  } catch (error: any) {
    const message = error?.response?.data?.message || 'Erro ao realizar login'
    notifyError(message)
  } finally {
    loading.value = false
  }
}

const handleRegister = async () => {
  if (registerForm.password !== registerForm.confirmPassword) {
    notifyError('As senhas precisam ser iguais.')
    return
  }

  try {
    loading.value = true
    await authStore.register(
      registerForm.name,
      registerForm.email,
      registerForm.password,
    )
    notifySuccess('Cadastro realizado com sucesso!')
    resetForms()
    redirectToHome()
  } catch (error: any) {
    const message = error?.response?.data?.message || 'Erro ao cadastrar'
    notifyError(message)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f0f4ff, #fef8f5);
  padding: 24px;
}

.auth-card {
  width: 100%;
  max-width: 420px;
}
</style>