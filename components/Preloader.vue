<script setup lang="ts">
document.documentElement.style.overflow = 'hidden'

onUnmounted(() => {
    document.documentElement.style.overflow = 'auto'
})
</script>
<template>
    <div class="w-screen h-screen z-max fixed isolate flex justify-center items-center bg-[rgba(255,255,255,0.75)]">
        <div class="container">
            <div
                class="w-16 aspect-square rounded-full bg-transparent border-b-transparent border-2 border-accent animate-spin">

            </div>
        </div>
    </div>
</template>
<style scoped>
.container {
    --delay: 2000ms;
    width: 100px;
    aspect-ratio: 1;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(4, 1fr);
}

.container [data-index="1"] {
    --color: #0096c7;
    grid-column: 1 / 5;
    grid-row: 1;
}

.container [data-index="1"] .color {
    width: 0;
    height: 100%;
    background-color: var(--color);
}

.container [data-index="2"] {
    --color: #00b4d8;
    grid-column: 5 / 6;
    grid-row: 1 / 4;
}

.container [data-index="2"] .color {
    height: 0;
    width: 100%;
    background-color: var(--color);

}

.container [data-index="3"] {
    --color: #48cae4;
    grid-column: 4 / 6;
    grid-row: 4 /5;
    background-color: var(--color);
}

.container [data-index="3"] .color {
    height: 100%;
    width: 100%;
    background-color: transparent;

}

.container [data-index="4"] {
    --color: #0096c7;
    grid-column: 1/4;
    grid-row: 4/5;
    background-color: var(--color);

}

.container [data-index="4"] .color {
    height: 100%;
    width: 100%;
    background-color: transparent;
}

.container [data-index="5"] {
    --color: #00b4d8;
    grid-column: 1 / 3;
    grid-row: 2 / 4;
    background-color: var(--color);
}

.container [data-index="5"] .color {
    height: 100%;
    width: 100%;
    background-color: transparent;
}

.container [data-index="6"] {
    --color: #90e0ef;
    grid-column: 3 / 5;
    grid-row: 2 / 4;
}

.container [data-index="6"] .color {
    height: 100%;
    width: 0;
    background-color: var(--color);
}
</style>