<template>
    <p v-if="error" class="error form-text text-danger">{{ error }}</p>
    <input
      class="fs-1"
      type="checkbox"
      :checked="props.todo.completed"
      @click="handleUpdateTodoStatus"
    />

    <input
      :class="['plain-input mx-4', { 
        'text-decoration-line-through': props.todo.completed 
        }]"
      type="text"
      v-model="props.todo.title"
      @blur="handleUpdateToDoItem"
      required
    />
    
</template>

<script setup>
  import { ref } from 'vue'
  import axios from 'axios'

  const props = defineProps({
    todo: {
      type: Object,
      required: true
    }
  })

  const emit = defineEmits(['todo-updated'])
  const token = localStorage.getItem('token')
  const BACKEND_URL = import.meta.env.VITE_URL
  const error = ref('')

  const handleUpdateTodoStatus = async () => {
    error.value = ''
    try 
    {
      const temp = !props.todo.completed
      await updateToDo({
        completed: temp,
      })
      emit('todo-updated')

    } 
    catch (error) 
    {
      console.error('Error updating status:', error)
    }
  }

  const handleUpdateToDoItem = async () => {
    error.value = ''
    if (props.todo.title.trim() === '') {
      error.value = "This field cannot be empty. Please try again."
      return
    } 

    try 
    {
      await updateToDo({
        title: props.todo.title,
      })
      emit('todo-updated')
    } 
    catch (err) 
    {
      console.error(err)
        if (err.response && err.response.status === 422) {
          error.value = "Exceeded character limit. Please try again."
        } else {
          error.value = 'Something went wrong. Please try again later.'
        }
    }
  }

  const updateToDo = async(updateData) => {
    return axios.put(`${BACKEND_URL}/api/v1/todos/${props.todo.id}`,
      updateData
    , { 
      headers: { 
        Authorization: `Bearer ${token}` 
      } 
    })
  }


</script>

<style scoped>
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
