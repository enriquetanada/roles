<template>
    <div>
        <b-badge variant="primary" v-for="item in items" :key="item.index">
            {{item}}
        </b-badge>
    </div>
</template>

<script>
export default {
    props: ['items'],
}
</script>
<style scoped>
    .badge {
        background-color: darkblue;
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 10px;
    }
</style>
