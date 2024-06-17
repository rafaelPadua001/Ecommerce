<template>
    {{ responsePayment }}
    Completed payment
</template>

<script>
    export default {
        name: 'Completed',
        props: ['responsePayment']
    }
</script>