<template>
    <div class="login-container bg-light p-5 ">
      <h2 class="mb-4">Login</h2>
      <form @submit.prevent="handleLogin">
        <div>
          <label>Email:</label>
          <input class="form-control" v-model="email" type="email" required />
        </div>
        <div>
          <label>Password:</label>
          <input class="form-control" v-model="password" type="password" required />
        </div>
        <p v-if="error" class="error">{{ error }}</p>
        <div class="d-grid gap-2">
          <button class="btn btn-primary rounded-5" type="submit" :disabled="loading">
            <Spinner v-if="loading" />
            <span v-else>Login</span>
          </button>
        </div>
      </form>
      <div class="d-grid gap-2 mt-2">
        <button class="btn btn-outline-primary rounded-5" @click="redirectToRegister">
            Register
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

  const email = ref('')
  const password = ref('')
  const loading = ref(false)
  const error = ref('')
  const router = useRouter()

  const handleLogin = async () => {
    error.value = ''
    loading.value = true
  
    try 
    {
      const response = await login()
  
      // console.log('Login successful', response)
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('username', response.data.user.name)

      router.push('/dashboard')
    } 
    catch (err) 
    {
      console.error(err)
      error.value = 'Login failed. Please check your credentials.'
    } 
    finally 
    {
      loading.value = false
    }
  }

  const login = async () => {
    return axios.post(`${BACKEND_URL}/api/auth/login`, { 
      email: email.value, 
      password: password.value 
    }, { 
      withCredentials: true 
    })
  }

  const redirectToRegister = async () => {
    router.push('/register')
  }

  </script>
  
  <style scoped>

  .login-container 
  {
    min-width: 400px;
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
  