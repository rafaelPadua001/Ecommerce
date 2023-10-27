<template>
    <v-container>
        <v-form @submit.prevent="submitForm">
            <v-text-field v-model="cardNumber" label="Número do Cartão" required></v-text-field>
            <v-text-field v-model="expiryDate" label="Data de Expiração (MM/AA)" required></v-text-field>
            <v-text-field v-model="cvv" label="CVV" required></v-text-field>
            <v-btn type="submit" color="primary">Pagar</v-btn>
        </v-form>
    </v-container>
</template>

<script>
    export default{
        data: () => ({
            cardNumber: false,
            expiryDate: false,
            cvv: false,
        })
    }
</script>