<script setup lang="ts">
useHead({
  titleTemplate: (titleChunk) => {
    return titleChunk ? `${titleChunk} - House Seekers Hub` : 'House Seekers Hub';
  },
})
useSeoMeta({
  ogSiteName: 'House Seekers Hub',
})

</script>
<template>
  <NuxtLoadingIndicator />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
