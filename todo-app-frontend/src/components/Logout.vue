<template>
    <button type="button" class="btn btn-outline-secondary btn-md rounded-5" @click="logout">
      Logout<i class="bi bi-box-arrow-right ms-2"></i>
    </button>
</template>

<script setup>
  import { useRouter } from 'vue-router';

  const router = useRouter()

  const logout = () => {
    localStorage.removeItem('token')
    router.push('/')
  }

</script>
