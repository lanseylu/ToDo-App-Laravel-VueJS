<template>
    <div class="register-container bg-light p-5">
      <h2 class="mb-4">Register</h2>
      <form @submit.prevent="handleRegistration">
        <div>
          <label>Name:</label>
          <input class="form-control" v-model="name" type="text" required />
        </div>
        <div>
          <label>Email:</label>
          <input class="form-control" v-model="email" type="email" required />
        </div>
        <div>
          <label>Password:</label>
          <input class="form-control" v-model="password" type="password" required />
        </div>
        <div>
          <label>Confirm Password:</label>
          <input class="form-control" v-model="password_confirmation" type="password" required />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <div class="d-grid gap-2">
          <button class="btn btn-primary rounded-5" type="submit" :disabled="loading">
            <Spinner v-if="loading" />
            <span v-else>Register</span>
          </button>
        </div>
      </form>
      <div class="d-grid gap-2 mt-2">
        <button class="btn btn-outline-primary rounded-5" @click="redirectToLogin">
            Login
        </button>
      </div>
      
    </div>
</template>
  
<script setup>
  import { ref, computed } from 'vue'
  import axios from 'axios'
  import { useRouter, RouterLink } from 'vue-router'
  import Spinner from './Spinner.vue'
  
  const BACKEND_URL = import.meta.env.VITE_URL
  
  const name = ref('')
  const email = ref('')
  const password = ref('')
  const password_confirmation = ref('')
  const loading = ref(false)
  const error = ref('')
  const router = useRouter()

  const handleRegistration = async () => {
    error.value = ''
    loading.value = true
  
    try 
    {
      await register()
      router.push('/') // Login Path
    } 
    catch (err) 
    {
      console.error(err)
      if (err.response && err.response.status === 422) {
        const errors = err.response.data.errors

        if (errors.email) {
            error.value = errors.email[0] 
        } else if (errors.password) {
            error.value = errors.password[0]  
        } else if (errors.name) {
            error.value = errors.name[0]
        } else {
            error.value = 'Validation failed. Please check your input.'
        }
      } else {
        error.value = 'Registration failed. Please try again later.'
      } 
    } 
    finally 
    {
        loading.value = false
    }

  }

  const register = async () => {
    return axios.post(`${BACKEND_URL}/api/auth/register`, { 
      name: name.value, 
      email: email.value, 
      password: password.value, 
      password_confirmation: password_confirmation.value 
    })
  }

  const redirectToLogin = async () => {
    router.push('/') // Login Path
  }

</script>
  
<style scoped>

  .register-container 
  {
    min-width: 500px;
    min-height: 300px;
    margin: 4rem auto;
    padding: 2rem;
    border: 1px solid #ccc;
    border-radius: 50px;
  }

  h2 {
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 800;
    }

  input 
  {
    width: 100%;
    padding: 0.5rem;
    margin: 0.5rem 0 1rem;
  }

  .error 
  {
    color: red;
    margin-top: 1rem;
  }

  </style>
  