<template>
    <div class="add-todo">
      <form @submit.prevent="submitTodo">
        <div class="input-group mb-3 mt-3">
            <input
            class="form-control"
            v-model="newTodo"
            type="text"
            placeholder="What do you want to do?"
            required
            autofocus
            />
            <button class="btn btn-primary add-button" type="submit">Add</button>
        </div>
       </form>
      <p v-if="error" class="error form-text text-danger">{{ error }}</p>
    </div>
    
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  const emit = defineEmits(['todo-added'])
  const newTodo = ref('')
  const error = ref('')
  const BACKEND_URL = import.meta.env.VITE_URL
  const token = localStorage.getItem('token')
  
  const submitTodo = async () => {
    
    error.value = ''

    if (newTodo.value.trim() === '') {
      error.value = 'This field cannot be empty.'
      return
    }
    
    try {
        const response = await createTodo(newTodo.value)
        
        emit('todo-added')
        
        newTodo.value = ''
    }
    catch(err)
    {
        console.error(err)
        if (err.response && err.response.status === 422) {
            error.value = "Exceeded character limit. Please try again."
        } else {
                error.value = 'Something went wrong. Please try again later.'
        }
    }
  }

  const createTodo = async (title) => {
    return axios.post(`${BACKEND_URL}/api/v1/todos`, {
        'title': title, 
      }, { 
        headers: {
            Authorization: `Bearer ${token}`
        }
    })
  }


  </script>
  
  <style scoped>
  .error {
    color: red;
    margin-top: 0.5rem;
  }

  input.inputToDO {
    border-radius: 5px;
    width: 80%;
    background-color: wheat;
    color: black;
    padding: 3px;
  }

  </style>
  