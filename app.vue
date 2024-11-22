<script setup lang="ts">
declare global {
  interface Window {
    handleCredentialResponse: (response: any) => void;
    onClickHandler: () => void;
  }
}
</script>
<template>
  <NuxtLoadingIndicator />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
