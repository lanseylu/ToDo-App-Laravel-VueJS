<template>
  <div class="container text-start bg-light p-4 rounded-5">

    <div class="d-flex justify-content-between">
      <div class="mb-3">
        <h2>
          <i class="bi bi-check-square-fill text-primary"></i>  Todo List
        </h2>
      </div>
      <div>
        <Logout />
      </div>
    </div>

    <p>Hi, <span class="fw-bold">{{ username }}</span>!</p>

    <AddToDoItem @todo-added="fetchToDoItems"/>

    <ul class="list-group list-group-flush" v-if="todos.length">
      <li class="list-group-item" v-for="todo in todos" :key="todo.id">
          <UpdateToDoItem v-bind:todo="todo" @todo-updated="fetchToDoItems" />
          <DeleteToDoItem v-bind:id="todo.id" @todo-deleted="fetchToDoItems"/>
      </li>  
    </ul>
    <p class="text-center pt-5" v-else>All caught up!</p>
    
    
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { defineEmits } from 'vue'
import UpdateToDoItem from './UpdateToDoItem.vue'
import Logout from './Logout.vue'
import AddToDoItem from './AddToDoItem.vue'
import DeleteToDoItem from './DeleteToDoItem.vue'


const todos = ref([])
const BACKEND_URL = import.meta.env.VITE_URL
const token = localStorage.getItem('token')
const emit = defineEmits(['todo-updated'])
const username = localStorage.getItem('username')


const fetchToDoItems = (async () => {
  try {
    const response = await axios.get(`${BACKEND_URL}/api/v1/todos`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    todos.value = response.data.data
  } 
  catch (error) 
  {
    console.error('Error fetching todos:', error)
  }
})

onMounted(fetchToDoItems)

const saveEdit = (async (todo) =>{
  try {
    if (!todo.title || todo.title.trim() === '') {
      fetchToDoItems()
      alert('Title cannot be empty.')
      return
    }

    const response = await axios.put(`${BACKEND_URL}/api/v1/todos/${todo.id}`, 
        { 'title': todo.title, },
        { headers: {
            Authorization: `Bearer ${token}`
        }
    })

    console.log(response)
  } 
  catch (error) 
  {
    console.error(error)
  }
})  

const updateTodoStatus = async (todo) => {
  try {
    todo.completed = !todo.completed;
    const response = await axios.put(`${BACKEND_URL}/api/v1/todos/${todo.id}`, 
        { completed: todo.completed },
        { headers: {
            Authorization: `Bearer ${token}`
        }
    })
    emit('todo-updated')
    console.log(response)
  } 
  catch (error) 
  {
    console.error('Error updating todo item:', error)
  }
}

</script>

<style scoped>

.container {
  min-width: 650px;
  min-height: 350px;
}

h2 {
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 800;
}

input.plain-input {
  min-width: 82%;
  border: none;
  background: transparent;
  font: inherit;
  color: inherit;
  padding: 0;
  margin: 0;
  outline: none;
}


</style>

