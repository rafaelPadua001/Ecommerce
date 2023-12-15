<template>
    <v-container>
        <v-row>
            <v-col>
                <v-sheet>
                    <h1>Aqui vou carregar os envios registrados</h1>
                    {{ shippments }}
                </v-sheet>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data: () => ({
            shippments: [],
        }),
        methods: {
            getShippments(){
                axios.get('/shippments')
                .then((response) => {
                    return this.shippments.push(response.data);
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
            }
        },
        created(){
            this.getShippments();
        }

    }
</script>