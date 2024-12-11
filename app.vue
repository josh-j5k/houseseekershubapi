<script setup lang="ts">
import type { user } from './types/user';

useHead({
  titleTemplate: (titleChunk) => {
    return titleChunk ? `${titleChunk} - House Seekers Hub` : 'House Seekers Hub';
  },
  htmlAttrs: {
    lang: 'en'
  },
  link: [
    {
      rel: 'icon',
      type: 'image/png',
      href: '/favicon-96x96.png'
    }
  ]
})
const authUser = <Ref<user | undefined>>useState('user')
const loading = useState('overlayLoader', () => false)
if (import.meta.client) {
  if (authUser.value == undefined) {
    const user = localStorage.getItem('user')
    if (user) {
      authUser.value = JSON.parse(user)
    }
  }

}

useSeoMeta({
  ogSiteName: 'House Seekers Hub',
})




</script>
<template>
  <NuxtLoadingIndicator />
  <Preloader v-if="loading" />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
