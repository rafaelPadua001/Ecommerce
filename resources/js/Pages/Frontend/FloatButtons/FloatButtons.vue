<template>
    <v-speed-dial v-model="fab" fixed right bottom>
        <v-row>
            <v-col>
                <v-fab icon="fa-brands fa-facebook"></v-fab>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-fab icon="fa-brands fa-whatsapp"></v-fab>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-fab icon="fas fa-phone"></v-fab>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-fab icon="fas fa-envelope"></v-fab>
            </v-col>
        </v-row>

    </v-speed-dial>
</template>

<script>
export default {
    data() {
        return {
            fab: false
        };
    }
};
</script>