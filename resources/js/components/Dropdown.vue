<template>
    <div class="dropdown relative">
        <!-- trigger -->
        <div @click.prevent="isOpen = !isOpen">
            <slot name="trigger"></slot>
        </div>

        <!-- menu links -->
        <div v-show="isOpen" class="dropdown-menu absolute bg-card py-2 rounded shadow mt-2"
             :class="align === 'left'? 'pin-l': 'pin-r'"
             :style="{width}">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            width: {default: 'auto'},
            align: {default: 'left'}
        },
        data() {
            return {isOpen: false}
        }
    }
</script>

<style scoped>

</style>
