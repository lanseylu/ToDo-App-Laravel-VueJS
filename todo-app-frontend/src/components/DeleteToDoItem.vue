<template>
    <button class="delete-button btn btn-md rounded-5" @click="handleToDoDelete"><i class="bi bi-trash"></i></button>
</template>

<script setup>
import { defineEmits, defineProps } from 'vue'
import axios from 'axios'

const props = defineProps({
  id: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['todo-deleted'])
const token = localStorage.getItem('token')
const BACKEND_URL = import.meta.env.VITE_URL

const handleToDoDelete = async () => {
    try {
        await deleteToDoItem()
        emit('todo-deleted')
        
    } catch (error) {
        console.error('Error deleting todo item:', error)
    }
}

const deleteToDoItem = async () => {
    return axios.delete(`${BACKEND_URL}/api/v1/todos/${props.id}`, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    })
}
</script>

<style scoped>

button.delete-button:hover {
    background-color: red;
    color: white;
}

</style>