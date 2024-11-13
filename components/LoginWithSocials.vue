<script setup lang="ts">


function redirectGoogle() {
    location.href = '/auth/google/redirect';

}
function facebookRedirect() {
    location.href = '/auth/facebook/redirect';

}
</script>

<template>
    <div class="grid grid-cols-3 items-center my-4">
        <hr class="w-full bg-slate-200">
        <p class="flex justify-center capitalize">Or</p>
        <hr class="w-full bg-slate-200">
    </div>
    <div class="pb-12">
        <button @click="redirectGoogle" type="button"
            class="bg-[#EA4335] mx-auto mb-4 w-56 h-10 text-white flex gap-3 justify-center rounded items-center">
            <span class="text-lg">
                <i class="fa-brands fa-google"></i>
            </span>
            <span>
                Continue with Google
            </span>
        </button>
        <button @click="facebookRedirect" disabled
            class="bg-[#4267B2] mx-auto w-56 h-10 text-white flex gap-3 justify-center rounded items-center">
            <span class="text-lg">
                <i class="fa-brands fa-facebook"></i>
            </span>
            <span>
                Continue with Facebook
            </span>
        </button>
    </div>
</template>